<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: Mayeul Akpovi (BeDigit - https://bedigit.com)
 *
 * LICENSE
 * -------
 * This software is provided under a license agreement and may only be used or copied
 * in accordance with its terms, including the inclusion of the above copyright notice.
 * As this software is sold exclusively on CodeCanyon,
 * please review the full license details here: https://codecanyon.net/licenses/standard
 */

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Traits\Common\AppendsTrait;
use App\Observers\PostPromotionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

#[ObservedBy([PostPromotionObserver::class])]
class PostPromotion extends BaseModel
{
	use AppendsTrait, HasFactory;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'post_promotions';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'post_id',
		'package_id',
		'payment_id',
		'promotion_type',
		'start_date',
		'end_date',
		'price',
		'currency_code',
		'status',
		'views_count',
		'clicks_count',
	];
	
	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'price' => 'decimal:2',
		'views_count' => 'integer',
		'clicks_count' => 'integer',
	];
	
	/**
	 * @var array<int, string>
	 */
	protected $appends = [
		'is_active',
		'is_expired',
		'remaining_days',
		'promotion_type_label',
		'status_label',
	];
	
	/**
	 * Available promotion types with their multilingual labels
	 */
	public const PROMOTION_TYPES = [
		'bump' => [
			'en' => 'Bump Up',
			'fr' => 'Remonter',
			'es' => 'Subir',
			'ar' => 'رفع الإعلان',
			'pt' => 'Promover',
			'de' => 'Nach oben',
			'it' => 'Promuovi',
			'ru' => 'Поднять',
			'zh' => '置顶',
			'ja' => '上げる',
		],
		'top' => [
			'en' => 'Top Ad',
			'fr' => 'Annonce en Tête',
			'es' => 'Anuncio Superior',
			'ar' => 'إعلان في المقدمة',
			'pt' => 'Anúncio no Topo',
			'de' => 'Top-Anzeige',
			'it' => 'Annuncio in Cima',
			'ru' => 'Топ объявление',
			'zh' => '顶部广告',
			'ja' => 'トップ広告',
		],
		'featured' => [
			'en' => 'Featured',
			'fr' => 'Mis en Avant',
			'es' => 'Destacado',
			'ar' => 'مميز',
			'pt' => 'Destaque',
			'de' => 'Hervorgehoben',
			'it' => 'In Evidenza',
			'ru' => 'Рекомендуемое',
			'zh' => '精选',
			'ja' => '注目',
		],
		'urgent' => [
			'en' => 'Urgent',
			'fr' => 'Urgent',
			'es' => 'Urgente',
			'ar' => 'عاجل',
			'pt' => 'Urgente',
			'de' => 'Dringend',
			'it' => 'Urgente',
			'ru' => 'Срочно',
			'zh' => '紧急',
			'ja' => '緊急',
		],
		'highlight' => [
			'en' => 'Highlight',
			'fr' => 'Surligner',
			'es' => 'Resaltar',
			'ar' => 'تسليط الضوء',
			'pt' => 'Destacar',
			'de' => 'Hervorheben',
			'it' => 'Evidenziare',
			'ru' => 'Выделить',
			'zh' => '突出显示',
			'ja' => 'ハイライト',
		],
	];
	
	/**
	 * Available statuses with their multilingual labels
	 */
	public const STATUSES = [
		'pending' => [
			'en' => 'Pending',
			'fr' => 'En Attente',
			'es' => 'Pendiente',
			'ar' => 'في الانتظار',
			'pt' => 'Pendente',
			'de' => 'Ausstehend',
			'it' => 'In Attesa',
			'ru' => 'Ожидание',
			'zh' => '待处理',
			'ja' => '保留中',
		],
		'active' => [
			'en' => 'Active',
			'fr' => 'Actif',
			'es' => 'Activo',
			'ar' => 'نشط',
			'pt' => 'Ativo',
			'de' => 'Aktiv',
			'it' => 'Attivo',
			'ru' => 'Активно',
			'zh' => '活跃',
			'ja' => 'アクティブ',
		],
		'expired' => [
			'en' => 'Expired',
			'fr' => 'Expiré',
			'es' => 'Expirado',
			'ar' => 'منتهي الصلاحية',
			'pt' => 'Expirado',
			'de' => 'Abgelaufen',
			'it' => 'Scaduto',
			'ru' => 'Истек',
			'zh' => '已过期',
			'ja' => '期限切れ',
		],
		'cancelled' => [
			'en' => 'Cancelled',
			'fr' => 'Annulé',
			'es' => 'Cancelado',
			'ar' => 'ملغى',
			'pt' => 'Cancelado',
			'de' => 'Storniert',
			'it' => 'Annullato',
			'ru' => 'Отменено',
			'zh' => '已取消',
			'ja' => 'キャンセル',
		],
	];
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	
	public function post(): BelongsTo
	{
		return $this->belongsTo(Post::class, 'post_id');
	}
	
	public function package(): BelongsTo
	{
		return $this->belongsTo(Package::class, 'package_id');
	}
	
	public function payment(): BelongsTo
	{
		return $this->belongsTo(Payment::class, 'payment_id');
	}
	
	public function currency(): BelongsTo
	{
		return $this->belongsTo(Currency::class, 'currency_code', 'code');
	}
	
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	
	public function scopeActive(Builder $builder): Builder
	{
		return $builder->where('status', 'active')
			->where('start_date', '<=', now())
			->where('end_date', '>=', now());
	}
	
	public function scopeExpired(Builder $builder): Builder
	{
		return $builder->where('status', 'expired')
			->orWhere('end_date', '<', now());
	}
	
	public function scopeByType(Builder $builder, string $type): Builder
	{
		return $builder->where('promotion_type', $type);
	}
	
	public function scopeByPost(Builder $builder, int $postId): Builder
	{
		return $builder->where('post_id', $postId);
	}
	
	/*
	|--------------------------------------------------------------------------
	| ACCESSORS | MUTATORS
	|--------------------------------------------------------------------------
	*/
	
	protected function isActive(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->status === 'active' 
				&& $this->start_date <= now() 
				&& $this->end_date >= now()
		);
	}
	
	protected function isExpired(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->status === 'expired' || $this->end_date < now()
		);
	}
	
	protected function remainingDays(): Attribute
	{
		return Attribute::make(
			get: function() {
				if ($this->isExpired) {
					return 0;
				}
				
				return max(0, now()->diffInDays($this->end_date, false));
			}
		);
	}
	
	protected function promotionTypeLabel(): Attribute
	{
		return Attribute::make(
			get: function() {
				$locale = app()->getLocale();
				return self::PROMOTION_TYPES[$this->promotion_type][$locale] ?? 
					self::PROMOTION_TYPES[$this->promotion_type]['en'] ?? 
					ucfirst($this->promotion_type);
			}
		);
	}
	
	protected function statusLabel(): Attribute
	{
		return Attribute::make(
			get: function() {
				$locale = app()->getLocale();
				return self::STATUSES[$this->status][$locale] ?? 
					self::STATUSES[$this->status]['en'] ?? 
					ucfirst($this->status);
			}
		);
	}
	
	/*
	|--------------------------------------------------------------------------
	| METHODS
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Activate the promotion
	 */
	public function activate(): bool
	{
		$this->status = 'active';
		$this->start_date = now();
		
		return $this->save();
	}
	
	/**
	 * Expire the promotion
	 */
	public function expire(): bool
	{
		$this->status = 'expired';
		
		return $this->save();
	}
	
	/**
	 * Cancel the promotion
	 */
	public function cancel(): bool
	{
		$this->status = 'cancelled';
		
		return $this->save();
	}
	
	/**
	 * Increment views count
	 */
	public function incrementViews(): bool
	{
		$this->increment('views_count');
		
		return true;
	}
	
	/**
	 * Increment clicks count
	 */
	public function incrementClicks(): bool
	{
		$this->increment('clicks_count');
		
		return true;
	}
	
	/**
	 * Get available promotion types for current locale
	 */
	public static function getPromotionTypes(): array
	{
		$locale = app()->getLocale();
		$types = [];
		
		foreach (self::PROMOTION_TYPES as $key => $labels) {
			$types[$key] = $labels[$locale] ?? $labels['en'] ?? ucfirst($key);
		}
		
		return $types;
	}
	
	/**
	 * Get available statuses for current locale
	 */
	public static function getStatuses(): array
	{
		$locale = app()->getLocale();
		$statuses = [];
		
		foreach (self::STATUSES as $key => $labels) {
			$statuses[$key] = $labels[$locale] ?? $labels['en'] ?? ucfirst($key);
		}
		
		return $statuses;
	}
	
	/*
	|--------------------------------------------------------------------------
	| HTML DISPLAY METHODS (for Admin Panel)
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Get post title HTML for admin display
	 */
	public function getPostTitleHtml(): string
	{
		if (!$this->post) {
			return '<span class="text-muted">Post not found</span>';
		}
		
		$url = url('posts/' . $this->post->id);
		return '<a href="' . $url . '" target="_blank">' . 
			str_limit($this->post->title, 50) . 
			' <i class="fas fa-external-link-alt"></i></a>';
	}
	
	/**
	 * Get promotion type HTML with badge
	 */
	public function getPromotionTypeHtml(): string
	{
		$colors = [
			'bump' => 'primary',
			'top' => 'success',
			'featured' => 'warning',
			'urgent' => 'danger',
			'highlight' => 'info',
		];
		
		$color = $colors[$this->promotion_type] ?? 'secondary';
		
		return '<span class="badge badge-' . $color . '">' . 
			$this->promotion_type_label . 
			'</span>';
	}
	
	/**
	 * Get package HTML for admin display
	 */
	public function getPackageHtml(): string
	{
		if (!$this->package) {
			return '<span class="text-muted">Package not found</span>';
		}
		
		return '<strong>' . $this->package->name . '</strong><br>' .
			'<small class="text-muted">' . $this->package->price . ' ' . $this->package->currency_code . '</small>';
	}
	
	/**
	 * Get status HTML with appropriate styling
	 */
	public function getStatusHtml(): string
	{
		$colors = [
			'pending' => 'warning',
			'active' => 'success',
			'expired' => 'secondary',
			'cancelled' => 'danger',
		];
		
		$color = $colors[$this->status] ?? 'secondary';
		
		$html = '<span class="badge badge-' . $color . '">' . $this->status_label . '</span>';
		
		if ($this->status === 'active') {
			$html .= '<br><small class="text-muted">' . 
				$this->remaining_days . ' days left</small>';
		}
		
		return $html;
	}
}