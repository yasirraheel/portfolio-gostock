<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use App\Models\User;
use App\Models\UsersReported;
use App\Models\Notifications;
use App\Models\Followers;
use App\Models\Like;
use App\Models\Replies;
use App\Models\Comments;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller {

	use Traits\UserTrait;

	 protected function validator(array $data, $id = null) {

    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

			return Validator::make($data, [
	        	'email'     => 'required|email|max:255|unique:users,id,'.$id,
	        ]);

    }

	 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
	 public function index()
	 {
		$query = request()->get('q');

		if ($query != '' && strlen($query) > 2) {
		 	$data = User::where('name', 'LIKE', '%'.$query.'%')
			->orWhere('username', 'LIKE', '%'.$query.'%')
		 	->orderBy('id','desc')->paginate(20);
		 } else {
		 	$data = User::orderBy('id','desc')->paginate(20);
		 }

    	return view('admin.members', ['data' => $data,'query' => $query]);
	 }

	/**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
	public function edit($id) {

		$data = User::findOrFail($id);

		if( $data->id == 1 || $data->id == Auth::user()->id ) {
			\Session::flash('info_message', trans('admin.user_no_edit'));
			return redirect('panel/admin/members');
		}
    	return view('admin.edit-member')->withData($data);

	}//<--- End Method

	/**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function update($id, Request $request) {

    $user = User::findOrFail($id);

	  $input = $request->all();

		if (! $request->authorized_to_upload) {
			$input['authorized_to_upload'] = 'no';
		}

	  $validator = $this->validator($input, $id);

		 if ($validator->fails()) {
	      return redirect()->back()
						 ->withErrors($validator)
						 ->withInput();
					 }

				 if ($request->status == 'suspended') {
					 $this->userSuspended($id);
				 }

    $user->fill($input)->save();

    \Session::flash('success_message', trans('admin.success_update'));

    return redirect('panel/admin/members');

	}//<--- End Method


	/**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */

	public function destroy($id) {

	 $user = User::findOrFail($id);

	 if( $user->id == 1 || $user->id == Auth::user()->id ) {
	 	return redirect('panel/admin/members');
		exit;
	 }

	 $this->deleteUser($id);

	 \Session::flash('success_message', trans('admin.success_delete'));

      return redirect('panel/admin/members');

	}//<--- End Method

	/**
	 * Login as a specific user (Admin only)
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function loginAsUser($id) {
		
		$user = User::findOrFail($id);
		
		// Security check - prevent admin from logging in as themselves
		if ($user->id == Auth::user()->id) {
			\Session::flash('error_message', trans('admin.cannot_login_as_self'));
			return redirect('panel/admin/members');
		}
		
		// Security check - prevent logging in as super admin (ID 1)
		if ($user->id == 1) {
			\Session::flash('error_message', trans('admin.cannot_login_as_super_admin'));
			return redirect('panel/admin/members');
		}
		
		// Store current admin ID in session for return functionality
		\Session::put('admin_id', Auth::user()->id);
		\Session::put('admin_name', Auth::user()->name);
		
		// Login as the selected user
		Auth::login($user);
		
		\Session::flash('success_message', trans('admin.success_login_as_user', ['name' => $user->name]));
		
		return redirect('/');
		
	}//<--- End Method

	/**
	 * Return to admin account
	 *
	 * @return Response
	 */
	public function returnToAdmin() {
		
		$adminId = \Session::get('admin_id');
		
		if (!$adminId) {
			\Session::flash('error_message', trans('admin.no_admin_session'));
			return redirect('/');
		}
		
		$admin = User::findOrFail($adminId);
		
		// Login as admin
		Auth::login($admin);
		
		// Clear admin session data
		\Session::forget('admin_id');
		\Session::forget('admin_name');
		
		\Session::flash('success_message', trans('admin.success_return_admin'));
		
		return redirect('panel/admin/members');
		
	}//<--- End Method

	public function userSuspended($id) {

		// Collections functionality removed for universal starter kit

	// Comments Delete
	$comments = Comments::where('user_id', '=', $id)->get();

	if( isset( $comments ) ){
		foreach($comments as $comment){
			$comment->delete();
		}
	}

	// Replies
	$replies = Replies::where('user_id', '=', $id)->get();

	if( isset( $replies ) ){
		foreach($replies as $replie){
			$replies->delete();
		}
	}

	// Likes
	$likes = Like::where('user_id', '=', $id)->get();
	if( isset( $likes ) ){
		foreach($likes as $like){
			$like->delete();
		}
	}

	// Downloads functionality removed for universal starter kit

	// Followers
	$followers = Followers::where( 'follower', $id )->orwhere('following',$id)->get();
	if( isset( $followers ) ){
		foreach($followers as $follower){
			$follower->delete();
		}
	}

	// Delete Notification
	$notifications = Notifications::where('author',$id)
	->orWhere('destination', $id)
	->get();

	if(isset( $notifications ) ){
		foreach($notifications as $notification){
			$notification->delete();
		}
	}

	// Images and Stock functionality removed for universal starter kit

	// User Reported
	$users_reporteds = UsersReported::where('user_id', '=', $id)->orWhere('id_reported', '=', $id)->get();

	if (isset($users_reporteds)) {
		foreach ($users_reporteds as $users_reported ) {
				$users_reported->delete();
			}// End
	}

	}


}
