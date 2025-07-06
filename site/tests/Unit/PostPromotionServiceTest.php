<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\PostPromotion;
use App\Models\Package;
use App\Services\PostPromotionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPromotionServiceTest extends TestCase
{
	use RefreshDatabase;
	
	protected PostPromotionService $service;
	
	protected function setUp(): void
	{
		parent::setUp();
		$this->service = app(PostPromotionService::class);
	}
	
	/** @test */
	public function it_can_create_promotion()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		
		$promotion = $this->service->createPromotion(
			$post->id,
			$package->id,
			'featured'
		);
		
		$this->assertInstanceOf(PostPromotion::class, $promotion);
		$this->assertEquals($post->id, $promotion->post_id);
		$this->assertEquals($package->id, $promotion->package_id);
		$this->assertEquals('featured', $promotion->promotion_type);
	}
	
	/** @test */
	public function it_can_activate_promotion_after_payment()
	{
		$promotion = PostPromotion::factory()->create(['status' => 'pending']);
		$paymentId = 123;
		
		$result = $this->service->activatePromotion($promotion->id, $paymentId);
		
		$this->assertTrue($result);
		
		$promotion->refresh();
		$this->assertEquals('active', $promotion->status);
		$this->assertEquals($paymentId, $promotion->payment_id);
	}
	
	/** @test */
	public function it_can_bump_post()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		
		$promotion = $this->service->bumpPost($post->id, $package->id);
		
		$this->assertInstanceOf(PostPromotion::class, $promotion);
		$this->assertEquals('bump', $promotion->promotion_type);
		$this->assertEquals($post->id, $promotion->post_id);
	}
	
	/** @test */
	public function it_expires_existing_bump_when_creating_new_one()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		
		// Create first bump
		$firstBump = $this->service->bumpPost($post->id, $package->id);
		$firstBump->update(['status' => 'active']);
		
		// Create second bump
		$secondBump = $this->service->bumpPost($post->id, $package->id);
		
		$this->assertEquals('expired', $firstBump->fresh()->status);
		$this->assertEquals('pending', $secondBump->status);
	}
	
	/** @test */
	public function it_can_make_top_ad()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		
		$promotion = $this->service->makeTopAd($post->id, $package->id);
		
		$this->assertEquals('top', $promotion->promotion_type);
	}
	
	/** @test */
	public function it_can_feature_post()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		
		$promotion = $this->service->featurePost($post->id, $package->id);
		
		$this->assertEquals('featured', $promotion->promotion_type);
	}
	
	/** @test */
	public function it_can_mark_post_urgent()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		
		$promotion = $this->service->markUrgent($post->id, $package->id);
		
		$this->assertEquals('urgent', $promotion->promotion_type);
	}
	
	/** @test */
	public function it_can_highlight_post()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		
		$promotion = $this->service->highlightPost($post->id, $package->id);
		
		$this->assertEquals('highlight', $promotion->promotion_type);
	}
	
	/** @test */
	public function it_can_get_active_promotions_for_post()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		
		// Create active promotion
		$activePromotion = PostPromotion::factory()->create([
			'post_id' => $post->id,
			'status' => 'active',
			'start_date' => now()->subDay(),
			'end_date' => now()->addDay(),
		]);
		
		// Create expired promotion
		PostPromotion::factory()->create([
			'post_id' => $post->id,
			'status' => 'expired',
		]);
		
		$activePromotions = $this->service->getActivePromotions($post->id);
		
		$this->assertCount(1, $activePromotions);
		$this->assertEquals($activePromotion->id, $activePromotions->first()->id);
	}
	
	/** @test */
	public function it_can_check_if_post_has_active_promotion()
	{
		$post = Post::factory()->create();
		
		// No promotions
		$this->assertFalse($this->service->hasActivePromotion($post->id, 'featured'));
		
		// Create active promotion
		PostPromotion::factory()->create([
			'post_id' => $post->id,
			'promotion_type' => 'featured',
			'status' => 'active',
			'start_date' => now()->subDay(),
			'end_date' => now()->addDay(),
		]);
		
		$this->assertTrue($this->service->hasActivePromotion($post->id, 'featured'));
		$this->assertFalse($this->service->hasActivePromotion($post->id, 'urgent'));
	}
	
	/** @test */
	public function it_can_expire_active_promotions()
	{
		$post = Post::factory()->create();
		
		$activePromotion = PostPromotion::factory()->create([
			'post_id' => $post->id,
			'promotion_type' => 'featured',
			'status' => 'active',
		]);
		
		$expiredCount = $this->service->expireActivePromotions($post->id, 'featured');
		
		$this->assertEquals(1, $expiredCount);
		$this->assertEquals('expired', $activePromotion->fresh()->status);
	}
	
	/** @test */
	public function it_can_expire_expired_promotions()
	{
		// Create promotion that should be expired
		PostPromotion::factory()->create([
			'status' => 'active',
			'end_date' => now()->subDay(),
		]);
		
		// Create promotion that should remain active
		PostPromotion::factory()->create([
			'status' => 'active',
			'end_date' => now()->addDay(),
		]);
		
		$expiredCount = $this->service->expireExpiredPromotions();
		
		$this->assertEquals(1, $expiredCount);
	}
	
	/** @test */
	public function it_can_get_promotion_statistics()
	{
		$post = Post::factory()->create();
		
		PostPromotion::factory()->create([
			'post_id' => $post->id,
			'promotion_type' => 'featured',
			'price' => 10.00,
			'views_count' => 100,
			'clicks_count' => 10,
		]);
		
		PostPromotion::factory()->create([
			'post_id' => $post->id,
			'promotion_type' => 'urgent',
			'price' => 5.00,
			'views_count' => 50,
			'clicks_count' => 5,
		]);
		
		$stats = $this->service->getPromotionStats($post->id);
		
		$this->assertEquals(2, $stats['total_promotions']);
		$this->assertEquals(150, $stats['total_views']);
		$this->assertEquals(15, $stats['total_clicks']);
		$this->assertEquals(15.00, $stats['total_spent']);
		$this->assertArrayHasKey('by_type', $stats);
	}
	
	/** @test */
	public function it_can_get_promoted_posts()
	{
		$post1 = Post::factory()->create();
		$post2 = Post::factory()->create();
		$post3 = Post::factory()->create();
		
		// Create active featured promotions
		PostPromotion::factory()->create([
			'post_id' => $post1->id,
			'promotion_type' => 'featured',
			'status' => 'active',
			'start_date' => now()->subDay(),
			'end_date' => now()->addDay(),
		]);
		
		PostPromotion::factory()->create([
			'post_id' => $post2->id,
			'promotion_type' => 'featured',
			'status' => 'active',
			'start_date' => now()->subDay(),
			'end_date' => now()->addDay(),
		]);
		
		// Create expired promotion
		PostPromotion::factory()->create([
			'post_id' => $post3->id,
			'promotion_type' => 'featured',
			'status' => 'expired',
		]);
		
		$promotedPosts = $this->service->getPromotedPosts('featured', 10);
		
		$this->assertCount(2, $promotedPosts);
		$postIds = $promotedPosts->pluck('id')->toArray();
		$this->assertContains($post1->id, $postIds);
		$this->assertContains($post2->id, $postIds);
		$this->assertNotContains($post3->id, $postIds);
	}
	
	/** @test */
	public function it_can_renew_promotion()
	{
		$oldPromotion = PostPromotion::factory()->create([
			'promotion_type' => 'featured',
			'status' => 'expired',
		]);
		
		$package = Package::factory()->promotion()->create();
		
		$newPromotion = $this->service->renewPromotion($oldPromotion->id, $package->id);
		
		$this->assertInstanceOf(PostPromotion::class, $newPromotion);
		$this->assertEquals($oldPromotion->post_id, $newPromotion->post_id);
		$this->assertEquals($oldPromotion->promotion_type, $newPromotion->promotion_type);
		$this->assertEquals($package->id, $newPromotion->package_id);
		$this->assertNotEquals($oldPromotion->id, $newPromotion->id);
	}
	
	/** @test */
	public function it_can_cancel_promotion()
	{
		$promotion = PostPromotion::factory()->create(['status' => 'active']);
		
		$result = $this->service->cancelPromotion($promotion->id);
		
		$this->assertTrue($result);
		$this->assertEquals('cancelled', $promotion->fresh()->status);
	}
}