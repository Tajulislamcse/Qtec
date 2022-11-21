<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use DB;
use Carbon\Carbon;
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
        $searches = [];
        $table = DB::table('searches');
      if(isset($keywords)||isset($users)||isset($from_date)||isset($to_date))
      {
       if(isset($keywords))
       {
         $searches = $table->whereIn('keyword',$keywords)->get();
       }
       if(isset($users))
       {
         $searches = $table->whereIn('user',$users)->get();
       }
      if(isset($from_date) ||isset($to_date))
      {
         if ($from_date != "" && $to_date != "") {
          //dd('test');
           $searches = $table->whereBetween('date', [$from_date, $to_date])->get();
        } elseif ($from_date != "") {
            $searches = $table->where('date','>=',$from_date)->get();
        }
      }
    }
      else
      {
       $searches = Search::all();

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
            return view('index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
