<?php
use Illuminate\Support\Facades\File;
use App\Models\Courses;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Intervention\Image\Facades\Image;
use Pawlox\VideoThumbnail\Facade\VideoThumbnail;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserBuyCourse;

function api_response_send($status = '', $message = '', $data = [], $code = '')
{
    $response['status'] = $status;
    $response['message'] = $message;

    $response['data'] = $data;
    if (empty($code)) {
        $code = 400;
    }
    return response()->json($response, $code);
}
