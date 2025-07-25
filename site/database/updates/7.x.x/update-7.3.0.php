<?php

use App\Exceptions\Custom\CustomException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

// ===| FILES |===
try {
	
	File::deleteDirectory(base_path('packages/mcamara/'));
	File::deleteDirectory(config_path('larapen/routes/'));
	File::deleteDirectory(database_path('seeds/'));
	File::delete(database_path('factories/ModelFactory.php'));
	File::delete(resource_path('views/vendor/admin/layouts/inc/maintenance.blade.php'));
	File::delete(storage_path('database/data.sql'));
	File::delete(storage_path('database/schema.sql'));
	File::delete(app_path('Helpers/Search/RawQueries.php'));
	File::delete(app_path('Http/Middleware/PreventBackHistory.php'));
	File::delete(app_path('Models/Scopes/FromActivatedCategoryScope.php'));
	
	$routesFiles = array_filter(glob(resource_path('lang/*/routes.php')), 'is_file');
	if (count($routesFiles) > 0) {
		foreach ($routesFiles as $filePath) {
			File::delete($filePath);
		}
	}
	
} catch (\Throwable $e) {
}

// ===| DATABASE |===
try {
	
	// settings
	DB::table('settings')->where('key', '=', 'currency_exchange')->update(['key' => 'currencyexchange']);
	DB::table('settings')->where('key', '=', 'domain_mapping')->update(['key' => 'domainmapping']);
	
	// posts
	if (!Schema::hasColumn('posts', 'accept_terms')) {
		Schema::table('posts', function (Blueprint $table) {
			$table->boolean('accept_terms')->nullable()->default(false)->after('verified_phone');
		});
	}
	if (!Schema::hasColumn('posts', 'accept_marketing_offers')) {
		Schema::table('posts', function (Blueprint $table) {
			$table->boolean('accept_marketing_offers')->nullable()->default(false)->after('accept_terms');
		});
	}
	
	// users
	if (Schema::hasColumn('users', 'receive_newsletter')) {
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('receive_newsletter');
		});
	}
	if (Schema::hasColumn('users', 'receive_advice')) {
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('receive_advice');
		});
	}
	if (!Schema::hasColumn('users', 'accept_terms')) {
		Schema::table('users', function (Blueprint $table) {
			$table->boolean('accept_terms')->nullable()->default(false)->after('verified_phone');
		});
	}
	if (!Schema::hasColumn('users', 'accept_marketing_offers')) {
		Schema::table('users', function (Blueprint $table) {
			$table->boolean('accept_marketing_offers')->nullable()->default(false)->after('accept_terms');
		});
	}
	
	// permissions
	$permissions = DB::table('permissions')->get();
	if ($permissions->count() > 0) {
		foreach ($permissions as $permission) {
			$name = preg_replace('/^(list|create|update|delete|install|access)\-(.+)/', '$2-$1', $permission->name);
			DB::table('permissions')->where('name', $permission->name)->update(['name' => $name]);
		}
	}
	
	// categories
	// Make Categories Slug Unique
	$slugsCollection = DB::table('categories')->selectRaw('slug, COUNT(*) countSlugs')->groupBy('slug')->having('countSlugs', '>', 1)->get();
	if ($slugsCollection->count() > 0) {
		foreach ($slugsCollection as $obj) {
			$cats = DB::table('categories')->where('slug', $obj->slug)->get();
			if ($cats->count() > 0) {
				foreach ($cats as $cat) {
					if (!isset($cat->translation_of) || !isset($cat->translation_lang)) {
						continue;
					}
					if ($cat->id != $cat->translation_of) {
						DB::table('categories')->where('id', $cat->id)
							->update(['slug' => $cat->slug . '-' . strtolower($cat->translation_lang)]);
					}
				}
			}
		}
	}
	
	// Convert all 0 'parent_id' value to null
	$tables = \App\Helpers\Common\DBUtils::getDatabaseTables(withPrefix: false);
	if (count($tables) > 0) {
		foreach ($tables as $table) {
			if (Schema::hasColumn($table, 'translation_lang')) {
				Schema::table($table, function (Blueprint $table) {
					$table->string('translation_lang', 10)->nullable()->change();
				});
			}
			if (Schema::hasColumn($table, 'translation_of')) {
				Schema::table($table, function (Blueprint $table) {
					$table->integer('translation_of')->unsigned()->nullable()->change();
				});
			}
			if (Schema::hasColumn($table, 'parent_id')) {
				Schema::table($table, function (Blueprint $table) {
					$table->integer('parent_id')->unsigned()->nullable()->change();
				});
				DB::table($table)->where('parent_id', 0)->update(['parent_id' => null]);
			}
		}
	}
	
} catch (\Throwable $e) {
	
	$message = $e->getMessage(). "\n" . 'in ' . str_replace(base_path(), '', __FILE__);
	throw new CustomException($message);
	
}
