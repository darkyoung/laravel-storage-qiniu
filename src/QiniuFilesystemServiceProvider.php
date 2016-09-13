<?php namespace wyzhcn\QiniuStorage;

use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use wyzhcn\QiniuStorage\Plugins\DownloadUrl;
use wyzhcn\QiniuStorage\Plugins\ImageExif;
use wyzhcn\QiniuStorage\Plugins\ImageInfo;
use wyzhcn\QiniuStorage\Plugins\ImagePreviewUrl;
use wyzhcn\QiniuStorage\Plugins\PersistentFop;
use wyzhcn\QiniuStorage\Plugins\PersistentStatus;
use wyzhcn\QiniuStorage\Plugins\PrivateDownloadUrl;
use wyzhcn\QiniuStorage\Plugins\UploadToken;
use wyzhcn\QiniuStorage\Plugins\Fetch;
use wyzhcn\QiniuStorage\Plugins\PutFile;

/**
 * Class QiniuFilesystemServiceProvider
 * @package wyzhcn\QiniuStorage
 */
class QiniuFilesystemServiceProvider extends ServiceProvider
{

    public function boot()
    {
        \Storage::extend(
            'qiniu',
            function ($app, $config) {
                $qiniu_adapter = new QiniuAdapter(
                    $config['access_key'],
                    $config['secret_key'],
                    $config['bucket'],
                    $config['domain'],
                    $config['secure']
                );
                $file_system = new Filesystem($qiniu_adapter);
                $file_system->addPlugin(new PrivateDownloadUrl());
                $file_system->addPlugin(new DownloadUrl());
                $file_system->addPlugin(new ImageInfo());
                $file_system->addPlugin(new ImageExif());
                $file_system->addPlugin(new ImagePreviewUrl());
                $file_system->addPlugin(new PersistentFop());
                $file_system->addPlugin(new PersistentStatus());
                $file_system->addPlugin(new UploadToken());
                $file_system->addPlugin(new Fetch());
                $file_system->addPlugin(new PutFile());

                return $file_system;
            }
        );
    }

    public function register()
    {
        //
    }
}
