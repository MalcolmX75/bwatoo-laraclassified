@extends('admin.layouts.master')

@section('header')
    <div class="row page-titles">
        <div class="col-md-6 col-12 align-self-center">
            <h3 class="mb-0">{{ trans('premiumads::messages.statistics') }}</h3>
        </div>
        <div class="col-md-6 col-12 align-self-center d-none d-md-flex justify-content-end">
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="{{ urlGen()->adminUrl() }}">{{ trans('admin.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ urlGen()->adminUrl('post_promotions') }}">Premium Ads</a></li>
                <li class="breadcrumb-item active">{{ trans('premiumads::messages.statistics') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Total Promotions -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="align-self-center">
                            <span class="display-6">{{ $stats['total_promotions'] ?? 0 }}</span>
                        </div>
                        <div class="align-self-center ml-auto">
                            <i class="fa-solid fa-star fa-2x text-warning"></i>
                        </div>
                    </div>
                    <p class="mb-0">{{ trans('premiumads::messages.total_promotions') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Active Promotions -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="align-self-center">
                            <span class="display-6">{{ $stats['active_promotions'] ?? 0 }}</span>
                        </div>
                        <div class="align-self-center ml-auto">
                            <i class="fa-solid fa-play fa-2x text-success"></i>
                        </div>
                    </div>
                    <p class="mb-0">{{ trans('premiumads::messages.active_promotions') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="align-self-center">
                            <span class="display-6">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</span>
                        </div>
                        <div class="align-self-center ml-auto">
                            <i class="fa-solid fa-dollar-sign fa-2x text-info"></i>
                        </div>
                    </div>
                    <p class="mb-0">{{ trans('premiumads::messages.total_revenue') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Monthly Revenue -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="align-self-center">
                            <span class="display-6">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</span>
                        </div>
                        <div class="align-self-center ml-auto">
                            <i class="fa-solid fa-calendar fa-2x text-primary"></i>
                        </div>
                    </div>
                    <p class="mb-0">{{ trans('premiumads::messages.monthly_revenue') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Promotion Types Chart -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('premiumads::messages.revenue_by_type') }}</h4>
                </div>
                <div class="card-body">
                    @if(!empty($stats['promotion_types']))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin.type') }}</th>
                                        <th>{{ trans('admin.count') }}</th>
                                        <th>{{ trans('admin.percentage') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['promotion_types'] as $type => $count)
                                        <tr>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ trans('premiumads::messages.promotion_types.' . $type) }}
                                                </span>
                                            </td>
                                            <td>{{ $count }}</td>
                                            <td>
                                                {{ $stats['total_promotions'] > 0 ? round(($count / $stats['total_promotions']) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">{{ trans('premiumads::messages.no_promotions') }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Status Distribution -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('premiumads::messages.promotions_by_status') }}</h4>
                </div>
                <div class="card-body">
                    @if(!empty($stats['status_distribution']))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin.status') }}</th>
                                        <th>{{ trans('admin.count') }}</th>
                                        <th>{{ trans('admin.percentage') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['status_distribution'] as $status => $count)
                                        <tr>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'active' => 'success',
                                                        'expired' => 'secondary',
                                                        'cancelled' => 'danger',
                                                    ];
                                                    $color = $statusColors[$status] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-{{ $color }}">
                                                    {{ trans('premiumads::messages.statuses.' . $status) }}
                                                </span>
                                            </td>
                                            <td>{{ $count }}</td>
                                            <td>
                                                {{ $stats['total_promotions'] > 0 ? round(($count / $stats['total_promotions']) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">{{ trans('premiumads::messages.no_promotions') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('premiumads::messages.monthly_stats') }}</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ trans('admin.year') }}: {{ date('Y') }}</p>
                    @if(!empty($stats['monthly_stats']))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin.month') }}</th>
                                        <th>{{ trans('admin.promotions') }}</th>
                                        <th>{{ trans('admin.revenue') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($month = 1; $month <= 12; $month++)
                                        @php
                                            $monthData = $stats['monthly_stats'][$month] ?? ['count' => 0, 'revenue' => 0];
                                        @endphp
                                        <tr>
                                            <td>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</td>
                                            <td>{{ $monthData['count'] }}</td>
                                            <td>${{ number_format($monthData['revenue'], 2) }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">{{ trans('premiumads::messages.no_promotions') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        // Add any JavaScript for charts if needed
        console.log('Premium Ads Statistics loaded');
    </script>
@endsection