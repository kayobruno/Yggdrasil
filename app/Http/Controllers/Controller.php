<?php

namespace App\Http\Controllers;

use App\Traits\Rest\ResponseHelpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Yggdrasil API",
 *      description="You can use X-localization (en or pt-br) header key to determine what language is used.",
 *      @OA\Contact(email="kayodw@gmail.com"),
 *      @OA\License(name="Apache 2.0", url="http://www.apache.org/licenses/LICENSE-2.0.html"),
 * )
 * @OA\Server(url=L5_SWAGGER_CONST_HOST)
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseHelpers;
}
