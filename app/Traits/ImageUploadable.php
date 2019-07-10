<?php
namespace App\Traits;

trait ImageUploadable
{
    public function uploadCropedImage()
    {
        $data = request('image');

        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);

        $data = base64_decode($data);
        $imageName = request('name');

            file_put_contents("uploads/".camelCaseToSlug()."/$_POST[path]/" . $imageName, $data);

    }

    public function imageCrop($id)
    {
        $name = str_singular(camelCaseToSlug(baseRoute(),'_'));
       // $model = "App\\" . baseRoute();
        $model = getModelName();
        ${$name} = $model::find($id);
        return view( $this->baseView . '.image-croper', compact($name));
    }
}