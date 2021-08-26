<?php

namespace Core\App\Traits;

use Illuminate\Http\Request;

trait IsController
{
    public function index(Request $request)
    {
        // $this->authorize('index',$this->model);
        $data = $this->model::select("*")->orderBy('created_at','desc');

        if(property_exists($this,'with')){
            $data = $data->with($this->with);
        }
        
        if($this->hasFilters($request->all())){
            $data = $data->filter($this->model::getFilters(),$request->all());
        }


        
        $data = $data->paginate(10);
        if($this->appends)
        $data->setCollection(collect($data->items())->each->append($this->appends));

        return  $this->successResponse($data);
    }

    private function hasFilters($arr){
        foreach ($this->model::getFilters() as $filter) {
            if(array_key_exists($filter['name'],$arr)){
                return true;
            }
        }
        return false;
    }

    public function show($id)
    {
        $a= $this->model::where('id',$id);

        if($this->with){
            $a = $a->with($this->with);
        }

        $a  = $a->first();
        if($this->appends){
            $a->append($this->appends);
        }
        // $this->authorize('show',$a);
        return $this->successResponse($a,'');
    }

    public function destroy($id)
    {
        $c = $this->model::findOrFail($id);
        $this->authorize('delete',$c);
        \DB::beginTransaction();
        try{
            $c->delete();
            \DB::commit();
            return $this->successResponse([],'',200);
        }
        catch(\Exception $e){
            
            \DB::rollback();
            return $this->errorResponse([], '',500,$e);
        }
    }
}