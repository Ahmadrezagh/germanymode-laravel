<?php

namespace App\Domains\AdminShell\Http\Controllers;

use App\Shared\Http\Controller;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $page): View|Response
    {
        $pages = config('admin_pages');

        if (! isset($pages[$page])) {
            abort(404);
        }

        $meta = $pages[$page];

        return view('admin.pages.show', [
            'pageKey' => $page,
            'meta' => $meta,
        ]);
    }
}
