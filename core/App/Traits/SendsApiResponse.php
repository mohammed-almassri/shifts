<?php

namespace Core\App\Traits;

use Exception;
use Throwable;

trait SendsApiResponse{

    protected function successResponse($data, $message='', $code=200)
	{
		return response()->json([
			'status'=> 'success', 
			'message' => $message, 
			'data' => $data
		], $code);
    }

	protected function errorResponse($data, $message='', $code=500,Throwable $e = null)
	{
		if($e){
			$trace = $e->getTrace();
			// \Log::info($trace);
			if(config('app.debug')){
				return response()->json(
					[
						$e->getMessage(),
						$trace
					]
				,$code);
			}
		}
		return response()->json([
			'status'=>'failed',
			'message' => $message,
			'data' => $data
		], $code);
    }


	protected function validationErrorResponse($data, $message='', $code=422,Throwable $e = null)
	{
		if($e){
			$trace = $e->getTrace();
			// \Log::info($trace);
			if(config('app.debug')){
				return response()->json(
					[
						$e->getMessage(),
						$trace
					]
				,$code);
			}
		}
		return response()->json([
			'status'=>'failed',
			'message' => $message,
			'errors' => $data
		], $code);
    }


}