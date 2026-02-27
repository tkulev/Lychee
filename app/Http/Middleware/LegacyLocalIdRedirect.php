<?php

/**
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2017-2018 Tobias Reich
 * Copyright (c) 2018-2026 LycheeOrg.
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LegacyLocalIdRedirect
{
	/**
	 * Handles redirection from old albums (Lychee v3 format).
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle(Request $request, \Closure $next)
	{
		$album_id = $request->route('albumId');

		if ($album_id !== null && strlen($album_id) === 14 && is_numeric($album_id)) {
			$album = DB::table('base_albums')->where('legacy_id', '=', $album_id)->first();
			if ($album === null) {
				throw new NotFoundHttpException();
			}

			// redirect to new gallery route with the resolved id
			return redirect()->route('gallery', $album->id, 301);
		}

		return $next($request);
	}
}
