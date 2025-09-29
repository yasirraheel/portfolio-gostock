<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
	public $timestamps = false;

	public static function users()
	{
		$sort      =  request()->get('sort');
		$location  =  request()->get('location');
		if ($sort == 'latest') {
			$sortQuery = 'users.id';
		} else if ($sort == 'photos') {
			$sortQuery = 'COUNT(images.id)';
		} else {
			$sortQuery = 'COUNT(followers.id)';
		}

		$data = User::where('users.status', 'active');

		// lOCATION
		if (isset($location) && $location != '') {
			$data->where('users.countries_id', $location);
		}

		// PHOTOS
		if ($sort == 'photos') {
			$data->leftjoin('images', 'users.id', '=', \DB::raw('images.user_id AND images.status = "active"'));
		}

		// POPULAR
		if ($sort == 'popular' || !$sort) {
			$data->leftjoin('followers', 'users.id', '=', \DB::raw('followers.following AND followers.status = "1"'));
		}

		$query = 	$data->where('users.status', '=', 'active')
			->groupBy('users.id')
			->orderBy(\DB::raw($sortQuery), 'DESC')
			->orderBy('users.id', 'ASC')
			->select(
				'users.id',
				'users.username',
				'users.name',
				'users.avatar',
				'users.cover',
				'users.status'
			)
			->with(['images' => function ($query) {
				$query->select('id', 'user_id', 'thumbnail')->take(3)->orderByDesc('id');
			}])
			->withCount(['images', 'followers'])
			->paginate(config('settings.result_request'))->onEachSide(1);

		return $query;
	}

	//Search
	public static function searchImages()
	{
		$q    = request()->get('q');
		$page = request()->get('page');
		$words = explode(' ', $q);
		$sort = request()->get('sort') == 'oldest' ? 'asc' : 'desc';

		if (count($words) == 1) {
			$images = Images::searchLike($q)->selectFieldsRelation()->orderBy('id', $sort)->paginate(config('settings.result_request'))->onEachSide(1);
		} else {
			$images = Images::search($q)
				->with(['author:id,avatar,name,username', 'stock:id,images_id,name,type,resolution'])
				->orderBy('id', $sort)
				->paginate(config('settings.result_request'))
				->onEachSide(1);
		}

		$title = __('misc.result_of') . ' ' . $q . ' - ';
		$total = $images->total();

		return ['images' => $images, 'page' => $page, 'title' => $title, 'total' => $total, 'q' => $q];
	}

	public static function latestImagesHome()
	{
		// Removed for universal starter kit - return empty collection
		return collect([]);
	}

	public static function latestImages()
	{
		// Removed for universal starter kit - return empty paginated collection
		return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
			'path' => request()->url(),
		]);
	}

	public static function featuredImages()
	{
		// Removed for universal starter kit - return empty paginated collection
		return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
			'path' => request()->url(),
		]);
	}

	public static function popularImages()
	{
		$query = Images::join('likes', function ($join) {
			$join->on('likes.images_id', '=', 'images.id')
				->where('images.status', 'active');
		});

		//=== Timeframe
		$query->when(request('timeframe') == 'today', function ($q) {
			$q->where('likes.date', '>=', Carbon::today()->toDateString());
		});

		$query->when(request('timeframe') == 'week', function ($q) {
			$q->whereBetween('likes.date', [
				Carbon::parse('now')->startOfWeek(),
				Carbon::parse('now')->endOfWeek(),
			]);
		});

		$query->when(request('timeframe') == 'month', function ($q) {
			$q->whereBetween('likes.date', [
				Carbon::parse('now')->startOfMonth(),
				Carbon::parse('now')->endOfMonth(),
			]);
		});

		$query->when(request('timeframe') == 'year', function ($q) {
			$q->whereYear('likes.date', date('Y'));
		});

		$data = $query->groupBy('likes.images_id')
			->orderByRaw('COUNT(likes.images_id) desc')
			->selectFieldsRelation()
			->paginate(config('settings.result_request'))->onEachSide(1);

		return $data;
	}

	public static function commentedImages()
	{
		$query = Images::join('comments', 'images.id', '=', 'comments.images_id')
			->where('images.status', 'active');

		//=== Timeframe
		$query->when(request('timeframe') == 'today', function ($q) {
			$q->where('comments.date', '>=', Carbon::today()->toDateString());
		});

		$query->when(request('timeframe') == 'week', function ($q) {
			$q->whereBetween('comments.date', [
				Carbon::parse('now')->startOfWeek(),
				Carbon::parse('now')->endOfWeek(),
			]);
		});

		$query->when(request('timeframe') == 'month', function ($q) {
			$q->whereBetween('comments.date', [
				Carbon::parse('now')->startOfMonth(),
				Carbon::parse('now')->endOfMonth(),
			]);
		});

		$query->when(request('timeframe') == 'year', function ($q) {
			$q->whereYear('comments.date', date('Y'));
		});

		$data = $query->groupBy('comments.images_id')
			->orderByRaw('COUNT(comments.images_id) desc')
			->selectFieldsRelation()
			->paginate(config('settings.result_request'))->onEachSide(1);

		return $data;
	}

	public static function viewedImages()
	{
		$query = Images::join('visits', 'images.id', '=', 'visits.images_id')
			->where('images.status', 'active');

		//=== Timeframe
		$query->when(request('timeframe') == 'today', function ($q) {
			$q->where('visits.date', '>=', Carbon::today()->toDateString());
		});

		$query->when(request('timeframe') == 'week', function ($q) {
			$q->whereBetween('visits.date', [
				Carbon::parse('now')->startOfWeek(),
				Carbon::parse('now')->endOfWeek(),
			]);
		});

		$query->when(request('timeframe') == 'month', function ($q) {
			$q->whereBetween('visits.date', [
				Carbon::parse('now')->startOfMonth(),
				Carbon::parse('now')->endOfMonth(),
			]);
		});

		$query->when(request('timeframe') == 'year', function ($q) {
			$q->whereYear('visits.date', date('Y'));
		});

		$data = $query->groupBy('visits.images_id')
			->orderByRaw('COUNT(visits.images_id) desc')
			->selectFieldsRelation()
			->paginate(config('settings.result_request'))->onEachSide(1);

		return $data;
	}

	public static function downloadsImages()
	{
		$query = Images::join('downloads', 'images.id', '=', 'downloads.images_id')
			->where('images.status', 'active');

		//=== Timeframe
		$query->when(request('timeframe') == 'today', function ($q) {
			$q->where('downloads.date', '>=', Carbon::today()->toDateString());
		});

		$query->when(request('timeframe') == 'week', function ($q) {
			$q->whereBetween('downloads.date', [
				Carbon::parse('now')->startOfWeek(),
				Carbon::parse('now')->endOfWeek(),
			]);
		});

		$query->when(request('timeframe') == 'month', function ($q) {
			$q->whereBetween('downloads.date', [
				Carbon::parse('now')->startOfMonth(),
				Carbon::parse('now')->endOfMonth(),
			]);
		});

		$query->when(request('timeframe') == 'year', function ($q) {
			$q->whereYear('downloads.date', date('Y'));
		});

		$data = $query->groupBy('downloads.images_id')
			->orderByRaw('COUNT(downloads.images_id) desc')
			->selectFieldsRelation()
			->paginate(config('settings.result_request'))->onEachSide(1);

		return $data;
	}

	public static function categoryImages($slug)
	{
		$category = Categories::with(['subcategories:id,category_id,name,slug'])->where('slug', '=', $slug)->firstOrFail();

		// Removed for universal starter kit - return empty paginated collection
		$images = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
			'path' => request()->url(),
		]);

		return ['images' => $images, 'category' => $category];
	}

	public static function subCategoryImages($slug, $subcategory)
	{
		$subcategory = Subcategories::with(['category:id,name,slug'])->where('slug', '=', $subcategory)->firstOrFail();

		// Removed for universal starter kit - return empty paginated collection
		$images = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
			'path' => request()->url(),
		]);

		return ['images' => $images, 'subcategory' => $subcategory];
	}

	public static function tagsImages($tags)
	{
		$images = Images::where('tags', 'LIKE', '%' . $tags . '%')
			->where('status', 'active')
			->groupBy('id')
			->orderBy('id', 'desc')
			->paginate(config('settings.result_request'))->onEachSide(1);

		$title = __('misc.tags') . ' - ' . $tags;

		$total = $images->total();

		return ['images' => $images, 'title' => $title, 'total' => $total, 'tags' => $tags];
	}

	public static function camerasImages($camera)
	{
		$images = Images::selectFieldsRelation()
			->where('camera', 'LIKE', '%' . $camera . '%')
			->where('status', 'active')
			->groupBy('id')
			->orderBy('id', 'desc')
			->paginate(config('settings.result_request'))->onEachSide(1);

		$title = __('misc.photos_taken_with') . ' ' . ucfirst($camera);

		$total = $images->total();

		return ['images' => $images, 'title' => $title, 'total' => $total, 'camera' => $camera];
	}

	public static function colorsImages($colors)
	{
		$images = Images::selectFieldsRelation()
			->where('colors', 'LIKE', '%' . $colors . '%')
			->where('status', 'active')
			->groupBy('id')
			->orderBy('id', 'desc')
			->paginate(config('settings.result_request'))->onEachSide(1);

		$title = __('misc.colors') . ' #' . $colors;

		$total = $images->total();

		return ['images' => $images, 'title' => $title, 'total' => $total, 'colors' => $colors];
	}

	public static function userImages($id)
	{
		$images = Images::selectFieldsRelation()
			->where('user_id', $id)
			->where('status', 'active')
			->groupBy('id')
			->orderBy('id', 'desc')
			->paginate(config('settings.result_request'))
			->onEachSide(1);

		return $images;
	}

	public static function premiumImages()
	{
		$data = Images::selectFieldsRelation()
			->where('item_for_sale', 'sale')
			->where('status', 'active')
			->orderBy('id', 'DESC')
			->paginate(config('settings.result_request'))
			->onEachSide(1);

		return $data;
	}

	public static function vectors()
	{
		$data = Images::selectFieldsRelation()
			->where('vector', 'yes')
			->orderBy('images.id', 'DESC')
			->paginate(config('settings.result_request'))
			->onEachSide(1);

		return $data;
	}
}
