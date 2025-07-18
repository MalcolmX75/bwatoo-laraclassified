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

namespace App\Models\Scopes;

use App\Http\Controllers\Web\Admin\SettingController;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ActiveScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
	 * @param \Illuminate\Database\Eloquent\Builder $builder
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
    public function apply(Builder $builder, Model $model): Builder
    {
    	// Load only activated entries on Settings selection
		if (str_contains(currentRouteAction(), SettingController::class)) {
			return $builder->where('active', 1);
		}
		
		// Load all entries for the Admin panel
        if (request()->segment(1) == urlGen()->adminUri()) {
            return $builder;
        }
        
        // Load only activated entries for the front
        return $builder->where('active', 1);
    }
}
