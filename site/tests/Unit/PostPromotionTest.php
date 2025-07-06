<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\PostPromotion;
use App\Models\Package;
use App\Models\Payment;
use App\Services\PostPromotionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PostPromotionTest extends TestCase
{
	use RefreshDatabase;
	
	protected PostPromotionService $promotionService;
	
	protected function setUp(): void
	{
		parent::setUp();
		$this->promotionService = app(PostPromotionService::class);
	}
	
	/** @test */
	public function it_can_create_a_post_promotion()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create(['price' => 10.00]);
		
		$promotion = $this->promotionService->createPromotion(
			$post->id,
			$package->id,
			'featured'
		);
		
		$this->assertInstanceOf(PostPromotion::class, $promotion);
		$this->assertEquals($post->id, $promotion->post_id);
		$this->assertEquals($package->id, $promotion->package_id);
		$this->assertEquals('featured', $promotion->promotion_type);
		$this->assertEquals('pending', $promotion->status);
		$this->assertEquals($package->price, $promotion->price);
	}
	
	/** @test */
	public function it_can_activate_a_promotion()
	{
		$promotion = PostPromotion::factory()->create(['status' => 'pending']);
		
		$result = $promotion->activate();
		
		$this->assertTrue($result);
		$this->assertEquals('active', $promotion->fresh()->status);
		$this->assertNotNull($promotion->fresh()->start_date);
	}
	
	/** @test */
	public function it_can_expire_a_promotion()
	{
		$promotion = PostPromotion::factory()->create(['status' => 'active']);
		
		$result = $promotion->expire();
		
		$this->assertTrue($result);
		$this->assertEquals('expired', $promotion->fresh()->status);
	}
	
	/** @test */
	public function it_can_check_if_promotion_is_active()
	{
		$activePromotion = PostPromotion::factory()->create([
			'status' => 'active',
			'start_date' => now()->subDay(),
			'end_date' => now()->addDay(),
		]);
		
		$expiredPromotion = PostPromotion::factory()->create([
			'status' => 'active',
			'start_date' => now()->subDays(2),
			'end_date' => now()->subDay(),
		]);
		
		$this->assertTrue($activePromotion->is_active);
		$this->assertFalse($expiredPromotion->is_active);
	}
	
	/** @test */
	public function it_can_calculate_remaining_days()
	{
		$promotion = PostPromotion::factory()->create([
			'status' => 'active',
			'start_date' => now(),
			'end_date' => now()->addDays(5),
		]);
		
		$this->assertEquals(5, $promotion->remaining_days);
	}
	
	/** @test */
	public function it_returns_zero_remaining_days_for_expired_promotion()
	{
		$promotion = PostPromotion::factory()->create([
			'status' => 'expired',
			'start_date' => now()->subDays(10),
			'end_date' => now()->subDays(5),
		]);
		
		$this->assertEquals(0, $promotion->remaining_days);
	}
	
	/** @test */
	public function it_can_get_promotion_type_label()
	{
		$promotion = PostPromotion::factory()->create(['promotion_type' => 'featured']);
		
		$this->assertEquals('Featured', $promotion->promotion_type_label);
	}
	
	/** @test */
	public function it_can_get_status_label()
	{
		$promotion = PostPromotion::factory()->create(['status' => 'active']);
		
		$this->assertEquals('Active', $promotion->status_label);
	}
	
	/** @test */
	public function it_can_increment_views_count()
	{
		$promotion = PostPromotion::factory()->create(['views_count' => 5]);
		
		$promotion->incrementViews();
		
		$this->assertEquals(6, $promotion->fresh()->views_count);
	}
	
	/** @test */
	public function it_can_increment_clicks_count()
	{
		$promotion = PostPromotion::factory()->create(['clicks_count' => 3]);
		
		$promotion->incrementClicks();
		
		$this->assertEquals(4, $promotion->fresh()->clicks_count);
	}
	
	/** @test */
	public function it_can_get_available_promotion_types()
	{
		$types = PostPromotion::getPromotionTypes();
		
		$this->assertIsArray($types);
		$this->assertArrayHasKey('bump', $types);
		$this->assertArrayHasKey('top', $types);
		$this->assertArrayHasKey('featured', $types);
		$this->assertArrayHasKey('urgent', $types);
		$this->assertArrayHasKey('highlight', $types);
	}
	
	/** @test */
	public function it_can_get_available_statuses()
	{
		$statuses = PostPromotion::getStatuses();
		
		$this->assertIsArray($statuses);
		$this->assertArrayHasKey('pending', $statuses);
		$this->assertArrayHasKey('active', $statuses);
		$this->assertArrayHasKey('expired', $statuses);
		$this->assertArrayHasKey('cancelled', $statuses);
	}
	
	/** @test */
	public function it_has_correct_relationships()
	{
		$post = Post::factory()->create();
		$package = Package::factory()->promotion()->create();
		$payment = Payment::factory()->create();
		
		$promotion = PostPromotion::factory()->create([
			'post_id' => $post->id,
			'package_id' => $package->id,
			'payment_id' => $payment->id,
		]);
		
		$this->assertInstanceOf(Post::class, $promotion->post);
		$this->assertInstanceOf(Package::class, $promotion->package);
		$this->assertInstanceOf(Payment::class, $promotion->payment);
		
		$this->assertEquals($post->id, $promotion->post->id);
		$this->assertEquals($package->id, $promotion->package->id);
		$this->assertEquals($payment->id, $promotion->payment->id);
	}
	
	/** @test */
	public function it_can_scope_by_active_promotions()
	{
		PostPromotion::factory()->create([
			'status' => 'active',
			'start_date' => now()->subDay(),
			'end_date' => now()->addDay(),
		]);
		
		PostPromotion::factory()->create([
			'status' => 'expired',
			'start_date' => now()->subDays(10),
			'end_date' => now()->subDay(),
		]);
		
		$activePromotions = PostPromotion::active()->get();
		
		$this->assertCount(1, $activePromotions);
		$this->assertEquals('active', $activePromotions->first()->status);
	}
	
	/** @test */
	public function it_can_scope_by_promotion_type()
	{
		PostPromotion::factory()->create(['promotion_type' => 'featured']);
		PostPromotion::factory()->create(['promotion_type' => 'urgent']);
		PostPromotion::factory()->create(['promotion_type' => 'featured']);
		
		$featuredPromotions = PostPromotion::byType('featured')->get();
		
		$this->assertCount(2, $featuredPromotions);
		$featuredPromotions->each(function ($promotion) {
			$this->assertEquals('featured', $promotion->promotion_type);
		});
	}
	
	/** @test */
	public function it_can_scope_by_post()
	{
		$post = Post::factory()->create();
		$otherPost = Post::factory()->create();
		
		PostPromotion::factory()->create(['post_id' => $post->id]);
		PostPromotion::factory()->create(['post_id' => $post->id]);
		PostPromotion::factory()->create(['post_id' => $otherPost->id]);
		
		$postPromotions = PostPromotion::byPost($post->id)->get();
		
		$this->assertCount(2, $postPromotions);
		$postPromotions->each(function ($promotion) use ($post) {
			$this->assertEquals($post->id, $promotion->post_id);
		});
	}
}