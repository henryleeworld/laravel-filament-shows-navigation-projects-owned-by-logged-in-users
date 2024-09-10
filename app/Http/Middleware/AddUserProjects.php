<?php

namespace App\Http\Middleware;

use App\Filament\Resources\ProjectResource;
use Closure;
use Filament\Navigation\NavigationItem;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddUserProjects
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        if (!filament()->getCurrentPanel()) {
            return $next($request);
        }

        $itemsList = [];

        $projects = auth()->user()->projects;

        foreach ($projects as $project) {
            $itemsList[] = NavigationItem::make($project->name)
                ->url(ProjectResource::getUrl('edit', ['record' => $project]))
                ->icon('heroicon-o-document')
                ->group(__('My Projects'));
        }

        filament()->getCurrentPanel()
            ->navigationItems($itemsList);

        return $next($request);
    }
}
