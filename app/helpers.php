<?php

if (! function_exists('get_item_slug')) {
    function get_item_slug()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_';
        $item_slug = '';

        $item_slug_found = true;

        while($item_slug_found)
        {
            for ($i = 0; $i < 11; $i++)
            {
                $item_slug .= $characters[mt_rand(0, 63)];
            }

            $item_exist = \App\Item::where('item_slug', $item_slug)->get()->count();
            if($item_exist == 0)
            {
                $item_slug_found = false;
            }
        }

        return $item_slug;
    }
}

if (! function_exists('site_already_installed')) {
    function site_already_installed()
    {
        return file_exists(storage_path('installed'));
    }
}

if (! function_exists('generate_symlink')) {
    function generate_symlink()
    {
        // create a storage symbolic link in public folder and website root before run installer
        //Artisan::call('key:generate');

        $target = storage_path('app'. DIRECTORY_SEPARATOR .'public');
        $link = public_path('storage');
        $blog_link = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'storage';
        $public_storage = $link;
        $root_storage = $blog_link;

        $vendor_target = $_SERVER["DOCUMENT_ROOT"]. DIRECTORY_SEPARATOR . 'vendor';
        $vendor_link = public_path('vendor');
        $vendor_symlink = $vendor_link;


        if(file_exists($public_storage))
        {
            unlink($public_storage);
        }
        if(file_exists($root_storage))
        {
            unlink($root_storage);
        }
        if(file_exists($vendor_symlink))
        {
            unlink($vendor_symlink);
        }

        symlink($target, $link);
        symlink($target, $blog_link);
        symlink($vendor_target, $vendor_link);
    }
}

if (! function_exists('is_demo_mode')) {
    function is_demo_mode()
    {
        return env("DEMO_MODE", false);
    }
}
