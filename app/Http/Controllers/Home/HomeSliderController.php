<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSlide;

class HomeSliderController extends Controller
{
    public function HomeSlider()
    {
        $homeslide = HomeSlide::find(1);
        return view('admin.home_slide.home_slide_all', compact('homeslide'));
    }

    public function UpdateSlider(Request $request)
    {
        $slide_id = $request->id;

        if ($request->file('home_slide')) {
            $image = $request->file('home_slide');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();  // 3434343443.jpg

            // Créer une nouvelle image à partir du fichier téléchargé
            $img = imagecreatefromstring(file_get_contents($image->getRealPath()));

            // Redimensionner l'image
            $resized_img = imagescale($img, 636, 852);

            // Enregistrer l'image redimensionnée
            $save_url = 'upload/home_slide/' . $name_gen;
            imagejpeg($resized_img, $save_url);

            HomeSlide::findOrFail($slide_id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'video_url' => $request->video_url,
                'home_slide' => $save_url,
            ]);

            $notification = [
                'message' => 'Home Slide Updated with Image Successfully',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);
        } else {
            HomeSlide::findOrFail($slide_id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'video_url' => $request->video_url,
            ]);

            $notification = [
                'message' => 'Home Slide Updated without Image Successfully',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);
        }
    }
}
