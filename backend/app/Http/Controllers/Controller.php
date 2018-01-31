<?php
/**
 * @SWG\Swagger(
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Nodes project",
 *         description="Nodes project.",
 *     ),
 *     consumes={"application/json"},
 *     produces={"application/json"},
 * )
 */
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
