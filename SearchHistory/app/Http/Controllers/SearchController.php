<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use Carbon\Carbon;
use App\Models\User;
use DB;
class SearchController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		//dd('test');

		if(request()->ajax())
		{
			$from_date = $request->from_date;
			$to_date = $request->to_date;
			$users = $request->users;
			$keywords = $request->keywords;
			$yesterday = $request->yesterday;
			$last_week  =  $request->last_week;
			$lastmonth = $request->lastmonth;
			$searches = [];
			$table = DB::table('searches as s')->join('users as u', 'u.id', '=', 's.user_id')->select('s.id','keyword','result','date','u.name as user');
			if(isset($keywords)||isset($users)||isset($from_date)||isset($to_date)||isset($yesterday) || isset($last_week)||isset($lastmonth))
			{
				if(isset($keywords))
				{
					$searches = $table->whereIn('keyword',$keywords)->get();
				}
				if(isset($users))
				{
					$searches =$table->whereIn('user',$users)->get();
				}
				if(isset($from_date) ||isset($to_date))
				{
					if ($from_date != "" && $to_date != "") 
					{
		  //dd('test');
						$searches = $table->whereBetween('date', [$from_date, $to_date])->get();
					} elseif ($from_date != "")
					{
						$searches = $table->where('date','>=',$from_date)->get();
					}
				}
				if(isset($yesterday))
				{
				  $searches = $table->where('date', '=', $yesterday)->get();
				}
				if (isset($last_week)) 
				{

					$last_week = explode('~', $request->last_week);
					$start_week = $last_week[0];
					$end_week = $last_week[1];
					$searches = $table->whereBetween('date', [$start_week, $end_week])->get();
				}
				if(isset($lastmonth))
				{

					$searches = $table->whereMonth('date', '=', $lastmonth)->get();
				}
			}
			else
			{
				$searches = DB::select("select s.id,keyword,result,date,u.name as user from searches s inner join users u on s.id = u.id");

			}
			return datatables()->of($searches)->make(true);
		}
	 // $country_name = DB::table('tbl_customer')
	 //      ->select('Country')
	 //      ->groupBy('Country')
	 //      ->orderBy('Country', 'ASC')
	 //      ->get();
	// return view('custom_search', compact('country_name'));
		//dd('tst');
	 //   $searches = Search::all();
		//dd($searches);
	   // dd('test');
		//return view('index',compact('searches'));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
        $users = User::all();
        $keywords = DB::table('searches')->select('keyword')->distinct()->get();
		return view('index',compact('users','keywords'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
	  $validatedData = $request->validate([
	  		'keyword' =>'required',
	  		'result' => 'required',
	  		'date' => 'required',
	  		'user' => 'required'
	  	]);
	    $data = [];
        $data = [
            'keyword'=>$request->keyword,
            'result'=>$request->result,
            'date' => date('Y-m-d',strtotime($request->date)),
            'user_id' => $request->user
        ];

       Search::create($data);

      return response()->json(['msg'=>'Record has been added successfully']);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
