<?php namespace App\Http\Controllers;

class AdminDashboardController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $this->middleware('admin');

        $sections = [
            ['link' => \Localization::transRoute('routes.countries'), 'text' => trans_choice('countries.countries', 2)],
            ['link' => \Localization::transRoute('routes.currencies'), 'text' => trans_choice('currencies.currencies', 2)],
            ['link' => \Localization::transRoute('routes.languages'), 'text' => trans_choice('languages.languages', 2)],
            ['link' => \Localization::transRoute('routes.locales'), 'text' => trans_choice('locales.locales', 2)],
            ['link' => \Localization::transRoute('routes.roles'), 'text' => trans_choice('roles.roles', 2)],
        ];

        return view('admin.dashboard', compact(
            'sections'
        ));
    }
}
