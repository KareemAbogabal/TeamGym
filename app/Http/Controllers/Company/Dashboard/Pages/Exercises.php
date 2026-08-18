<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Front\Client;
use App\Models\Back\Activity;
use App\Models\Back\ActivityElements;
use App\Models\Back\ActivityAttachments;
use App\Models\Back\FilesExercises;
use App\Models\Back\Lineage;
use App\Traits\GetLineage;
use Carbon\Carbon;

class Exercises extends Controller {
  use GetLineage;
  public function index(Request $request) {
    $clients = Client::all();
    $activities = Activity::with('elements.attachments')->get();
    $categories = ['supplement', 'input', 'revenues', 'expenses'];
    $dataGetLineage = [];
    $dataGetArray = [];
    foreach ($categories as $c) {
      $dataGetArray[$c] = $this->getArray(Lineage::class, "$c", false);
    };
    foreach ($categories as $c) {
      $dataGetLineage[$c] = $this->get(Lineage::class, "$c", false);
    };
    return view('Company.Dashboard.Pages.exercises', compact("clients", "activities", "dataGetArray", "dataGetLineage"));
  }
  public function getActivityCustomer(Request $request) {
    $data = $request->validate([
      'code' => ['required', 'string'],
    ]);
    $code = $request->input("code");
    $activity = Activity::where('code_client', $code)->with(['elements.attachments'])->get();
    if (!$activity) {
      return response()->json(['check' => 'no'], 200);
    };
    return response()->json($activity, 200);
  }
  public function addExercises(Request $request) {
    $request->validate([
      'client_code' => ['required','string'],
      'exercise_name' => ['required','array'],
      'exercise_name.*' => ['required'],
      'exercise_name.*.*' => ['nullable','string','max:255'],
      'description' => ['required','array'],
      'description.*' => ['required'],
      'description.*.*' => ['nullable','string','max:255'],
      'shape' => ['required','array'],
      'shape.*' => ['required'],
      'shape.*.*' => ['required','string','max:255'],
      'groups' => ['required','array'],
      'groups.*' => ['required'],
      'groups.*.*' => ['required','string','max:255'],
      'repetitions' => ['required','array'],
      'repetitions.*' => ['required'],
      'repetitions.*.*' => ['required','string','max:255'],
      'video' => ['nullable','array'],
      'video.*' => ['nullable','array'],
      'video.*.*' => ['nullable','mimes:mp4,webm,mov,avi,ogg','max:10240'],
      'img' => ['nullable','array'],
      'img.*' => ['nullable','array'],
      'img.*.*' => ['nullable','mimes:png,jpg,jpeg','max:5120'],
    ]);
    $clientCode = $request->input('client_code');
    $employee = Auth::guard('employee')->user();
    $exerciseNames = $request->input('exercise_name', []);
    $times = $request->input('times', []);
    $description = $request->input('description', []);
    $shapes = $request->input('shape', []);
    $groups = $request->input('groups', []);
    $repetitions = $request->input('repetitions', []);
    $videos = $request->file('video', []);
    $imgs = $request->file('img', []);
    $month = Carbon::now('Africa/Cairo')->format('F');
    foreach ($exerciseNames as $exIndex => $exRaw) {
      $exName = is_array($exRaw) ? ($exRaw[0] ?? '') : $exRaw;
      if (trim($exName) === '') continue;
      $code = rand(time(), 100000);
      $rand = rand(time(), 100000);
      $activity = new Activity();
      $activity->code = $code;
      $activity->code_client = $clientCode;
      $activity->code_employee = $employee->code;
      $activity->name = (string)$exName;
      $activity->state = "exercise";
      $activity->statement = "true";
      $descRaw = $description[$exIndex] ?? '';
      if (is_array($descRaw)) {
        $descParts = array_filter(array_map('trim', $descRaw), function($v) { return $v !== ''; });
        $descString = count($descParts) ? implode(' ', $descParts) : '';
      } else {
        $descString = trim((string)$descRaw);
      };
      $activity->description = $descString;
      $activity->code_attachments = (string)$rand;
      $activity->month = $month;
      $timesRaw = $times[$exIndex] ?? '';
      $activity->times = is_array($timesRaw) ? (string) ($timesRaw[0] ?? '') : (string) $timesRaw;
      $activity->visits = 0;
      $activity->save();
      $shapeList = isset($shapes[$exIndex]) ? (array)$shapes[$exIndex] : [];
      $groupList = isset($groups[$exIndex]) ? (array)$groups[$exIndex] : [];
      $repList = isset($repetitions[$exIndex]) ? (array)$repetitions[$exIndex] : [];
      $videoListForEx = $videos[$exIndex] ?? [];
      $imgListForEx = $imgs[$exIndex] ?? [];
      foreach ($shapeList as $shapeIdx => $shapeName) {
        $ratioVal = $groupList[$shapeIdx] ?? '';
        $setsVal  = $repList[$shapeIdx] ?? '';
        $element = new ActivityElements();
        $element->code_activities = $activity->code_attachments;
        $element->name = (string)$shapeName;
        $element->ratio = (string)$ratioVal;
        $element->sets = (string)$setsVal;
        $element->save();
        $savedVideoPath = null;
        $savedImgPath  = null;
        $nameShape = Str::slug($shapeName);
        $checkFilesExercises = FilesExercises::where("name", $nameShape)->first();
        if (!$checkFilesExercises) {
          $filesExercises = new FilesExercises();
          $randFile = rand(time(), 100000);
          $targetDirVideo = public_path("video/exercises/$nameShape");
          if (!File::exists(public_path("video/exercises/$nameShape"))) {
            File::makeDirectory(public_path("video/exercises/$nameShape"), 0777, true, true);
          };
          $targetDirImg = public_path("images/exercises/$nameShape");
          if (!File::exists(public_path("images/exercises/$nameShape"))) {
            File::makeDirectory(public_path("images/exercises/$nameShape"), 0777, true, true);
          };
          if (isset($videoListForEx[$shapeIdx]) && $videoListForEx[$shapeIdx] !== null) {
            $videoFile = $videoListForEx[$shapeIdx];
            if (is_array($videoFile)) $videoFile = reset($videoFile) ?: null;
            if ($videoFile && $videoFile->isValid()) {
              $videoName = time() . $videoFile->getClientOriginalName();
              $videoFile->move($targetDirVideo, $videoName);
              $savedVideoPath = "video/exercises/$nameShape/{$videoName}";
            };
          };
          if (isset($imgListForEx[$shapeIdx]) && $imgListForEx[$shapeIdx] !== null) {
            $imgFile = $imgListForEx[$shapeIdx];
            if (is_array($imgFile)) $imgFile = reset($imgFile) ?: null;
            if ($imgFile && $imgFile->isValid()) {
              $imgName = time() . $imgFile->getClientOriginalName();
              $imgFile->move($targetDirImg, $imgName);
              $savedImgPath = "images/exercises/$nameShape/{$imgName}";
            };
          };
          $filesExercises->code = $randFile;
          $filesExercises->name = $nameShape;
          $filesExercises->pathImg = $savedImgPath;
          $filesExercises->pathVideo = $savedVideoPath;
          $filesExercises->save();
          if ($savedVideoPath || $savedImgPath) {
            $attachment = new ActivityAttachments();
            $attachment->code = $element->id;
            $attachment->img = $savedImgPath ?? '';
            $attachment->video = $savedVideoPath ?? '';
            $attachment->save();
          };
        } else {
          $attachment = new ActivityAttachments();
          $attachment->code = $element->id;
          $attachment->img = $checkFilesExercises->pathImg ?? '';
          $attachment->video = $checkFilesExercises->pathVideo ?? '';
          $attachment->save();
        };
      };
    };
    notifySuccess(__('messages.saved-successfully'));
    return redirect()->back();
  }
  public function checkShape(Request $request) {
    $name = trim($request->input('name', ''));
    $nameShape = Str::slug($name);
    if ($nameShape === '') {
      return response()->json(['result' => 'no']);
    };
    $pathImg = public_path("images/exercises/{$nameShape}");
    $pathVideo = public_path("video/exercises/{$nameShape}");
    $existsImg = File::exists($pathImg);
    $existsVideo = File::exists($pathVideo);
    if ($existsImg || $existsVideo) {
      return response()->json(['result' => 'yes']);
    };
    return response()->json(['result' => 'no']);
  }
  public function addFoods(Request $request) {
    $request->validate([
      'client_code' => ['required','string'],
      'meal' => ['required','array'],
      'meal.*' => ['required'],
      'meal.*.*' => ['nullable','string','max:255'],
      'often' => ['required','array'],
      'often.*' => ['required'],
      'often.*.*' => ['nullable','string','max:255'],
      'quantity' => ['required','array'],
      'quantity.*' => ['required'],
      'quantity.*.*' => ['required','string','max:255'],
    ]);
    $month = Carbon::now('Africa/Cairo')->format('F');
    $clientCode = $request->input('client_code');
    $employee = Auth::guard('employee')->user();
    $meal = $request->input('meal', []);
    $often = $request->input('often', []);
    $quantity = $request->input('quantity', []);
    $code = rand(time(), 100000);
    $rand = rand(time(), 100000);
    $activity = new Activity();
    $activity->code = $code;
    $activity->code_client = $clientCode;
    $activity->code_employee = $employee->code;
    $activity->name = "Foods";
    $activity->description = "";
    $activity->state = "foods";
    $activity->statement = "true";
    $activity->code_attachments = (string)$rand;
    $activity->month = $month;
    $activity->times = 1;
    $activity->visits = 0;
    $activity->save();
    foreach ($meal as $index => $item) {
      $element = new ActivityElements();
      $element->code_activities = $activity->code_attachments;
      $element->name = (string)$meal[$index];
      $element->ratio = (string)$often[$index];
      $element->sets = (string)$quantity[$index];
      $element->save();
    };
    notifySuccess(__('messages.saved-successfully'));
    return redirect()->back();
  }
  public function updateCoulmn(Request $request) {
    $request->validate([
      'file' => ['required', "file"],
      'id' => ['required'],
      'state' => ['required', 'in:img,video'],
    ]);
    $file = $request->file('file');
    $id = $request->input('id');
    $state = $request->input('state');
    $attachments = ActivityAttachments::find($id);
    $attachmentsAll = ActivityAttachments::all();
    $oldImg = $attachments->img;
    $oldVideo = $attachments->video;
    if (!$attachments) {
      Log::warning('updateCoulmn: attachments not found', ['id' => $id]);
      notifyError('attachments not found.');
      return redirect()->back();
    };
    $element = ActivityElements::find($attachments->code);
    if (!$element) {
      Log::warning('updateCoulmn: element not found for attachment', ['attachment_id' => $id, 'element_code' => $attachments->code]);
      notifyError('element not found.');
      return redirect()->back();
    };
    $name = Str::slug($element->name);
    $filesExercises = FilesExercises::where('name', $name)->first();
    try {
      if ($state === "img") {
        $oldPath = $attachments->img ? public_path($attachments->img) : null;
        $dir = public_path("images/exercises/{$name}");
        if ($oldPath && File::exists($oldPath)) {
          File::delete($oldPath);
        };
        if (!File::exists($dir)) {
          File::makeDirectory($dir, 0755, true);
        };
        $imgName = time() . '_' . $file->getClientOriginalName();
        $file->move($dir, $imgName);
        $newRel = "images/exercises/{$name}/{$imgName}";
        $attachments->img = $newRel;
        $attachments->save();
        ActivityAttachments::where('img', $oldImg)->update(['img' => $newRel]);
        if ($filesExercises) {
          $filesExercises->pathImg = $newRel;
          $filesExercises->save();
        };
      } else {
        $oldPath = $attachments->video ? public_path($attachments->video) : null;
        $dir = public_path("video/exercises/{$name}");
        if ($oldPath && File::exists($oldPath)) {
          File::delete($oldPath);
        };
        if (!File::exists($dir)) {
          File::makeDirectory($dir, 0755, true);
        };
        $videoName = time() . '_' . $file->getClientOriginalName();
        $file->move($dir, $videoName);
        $newRel = "video/exercises/{$name}/{$videoName}";
        $attachments->video = $newRel;
        $attachments->save();
        ActivityAttachments::where('video', $oldVideo)->update(['video' => $newRel]);
        if ($filesExercises) {
          $filesExercises->pathVideo = $newRel;
          $filesExercises->save();
        };
      };
      notifySuccess(__('messages.updated-successfully'));
      return redirect()->back();
    } catch (\Throwable $e) {
      Log::error('updateCoulmn exception', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'input' => $request->all(),
        'attachment_id' => $id,
      ]);
      notifyError('حدث خطأ أثناء معالجة الملف. راجع السجل.');
      return redirect()->back();
    };
  }
  public function destroy(Request $request) {
    $request->validate([
      'id' => ['required'],
      'state' => ['required'],
    ]);
    $id = $request->input("id");
    $state = $request->input("state");
    if ($state == "main") {
      $activity = Activity::find($id);
      if ($activity) {
        // $dirImg = public_path("images/exercises/{$activity->id}");
        // if (File::exists($dirImg)) {
        //   File::deleteDirectory($dirImg);
        // };
        // $dirVideo = public_path("video/exercises/{$activity->id}");
        // if (File::exists($dirVideo)) {
        //   File::deleteDirectory($dirVideo);
        // };
        $activity->delete();
      };
    } else {
      $element = ActivityElements::find($id);
      $activity = Activity::where("code_attachments", $element->code_activities)->first();
      $attachment = ActivityAttachments::where('code', $element->id)->first();
      $attachmentsCount = ActivityElements::all();
      $uniqueElements = $attachmentsCount->groupBy('code_activities')->filter(fn($group) => $group->count() === 1)->flatMap(fn($group) => $group);
      if ($attachment) {
        // $dirImg = public_path($attachment->img);
        // if (File::exists($dirImg) && is_dir($dirImg)) {
        //   File::delete($dirImg);
        // };
        // $dirVideo = public_path($attachment->video);
        // if (File::exists($dirVideo) && is_dir($dirVideo)) {
        //   File::delete($dirVideo);
        // };
        foreach ($uniqueElements as $u) {
          if ($u->id == $element->id) {
            // $dirImg = public_path("images/exercises/{$element->id}");
            // if (File::exists($dirImg)) {
            //   File::deleteDirectory($dirImg);
            // };
            // $dirVideo = public_path("video/exercises/{$element->id}");
            // if (File::exists($dirVideo)) {
            //   File::deleteDirectory($dirVideo);
            // };
            $activity->delete();
          };
        };
        $attachment->delete();
      };
      $element->delete();
    };
    notifySuccess(__('messages.deleted-successfully'));
    return redirect()->route("exercise");
  }
}
