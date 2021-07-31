<?php

namespace App\Http\Controllers;

use App\Models\BoardModel;
use Exception, Log;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(){
        $boardId = 0;
        $columns = [];
        $board = BoardModel::find(1);
        if($board){
            $boardId = $board->id;
            $columns = $board->columns;
        }

        $data = [
            'boardId' => $boardId,
            'columns' => $columns 
        ];
        return view('board', $data);
    }

    public function store(Request $request){
        try{
            $board = BoardModel::find($request->board_id);
            if(!$board){
                $board = new BoardModel();
            }
    
            $board->columns = json_decode($request->columns);
            $board->save();

            return response()->json([
                'status' => 'success',
                'board' => $board,
            ]);


        }catch(Exception $error){
            Log::info('BoardController@store error message: ' . $error->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => "Unable to complete request. Try again."
            ], 500);
        }
        

    }

    public function dumpSQL(){
        try{
            $databaseName = config('database.database');
            $userName = config('database.username');
            $password = config('database.password');
            $uniqueTime = time();
            $fileName = storage_path("app/public/board-dump-$uniqueTime.sql");
            \Spatie\DbDumper\Databases\MySql::create()
                ->setDbName($databaseName)
                ->setUserName($userName)
                ->setPassword($password)
                ->excludeTables(['migrations'])
                ->dumpToFile($fileName);
            return response()->download($fileName)->deleteFileAfterSend(true);
        }catch(Exception $error){
            abort(500);
        }
        

    }
}
