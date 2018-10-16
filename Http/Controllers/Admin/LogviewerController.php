<?php

namespace Modules\Logviewer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Logviewer\Services\ServiceLogViewer;
use Illuminate\Support\Facades\Crypt;

class LogviewerController extends AdminBaseController
{

    private $logviewer;
    private $request;

    public function __construct(ServiceLogViewer $logViewer, Request $request)
    {
        parent::__construct();
        $this->logviewer = $logViewer;
        $this->request = $request;

    }

    public function index()
    {
        $folderFiles = [];
        if ($this->request->input('folder')) {
            $this->logviewer->setFolder(Crypt::decrypt($this->request->input('folder')));
            $folderFiles = $this->logviewer->getFolderFiles(true);
        }
        if ($this->request->input('file')) {
            $this->logviewer->setFile(Crypt::decrypt($this->request->input('file')));
        }

        if ($this->request->input('dl')) {
            return \response()->download($this->pathFromInput('dl'));
        }
        if ($this->request->has('clean')) {
            File::put($this->pathFromInput('clean'), '');
            return redirect($this->request->url());
        }

        if ($this->request->has('del')) {
            File::delete($this->pathFromInput('del'));
            return redirect($this->request->url());
        }

        if ($this->request->has('delall')) {
            $files = ($this->logviewer->getFolderName())
                ? $this->logviewer->getFolderFiles(true)
                : $this->logviewer->getFiles(true);
            foreach ($files as $file) {
                File::delete($this->logviewer->pathToLogFile($file));
            }
            return redirect($this->request->url());
        }

        $data_logs = [
            'logs' => $this->logviewer->all(),
            'folders' => $this->logviewer->getFolders(),
            'current_folder' => $this->logviewer->getFolderName(),
            'folder_files' => $folderFiles,
            'files' => $this->logviewer->getFiles(true),
            'current_file' => $this->logviewer->getFileName(),
            'standardFormat' => true,
        ];

        if (is_array($data_logs['logs'])) {
            $firstLog = reset($data_logs['logs']);
            if (!$firstLog['context'] && !$firstLog['level']) {
                $data_logs['standardFormat'] = false;
            }
        }
        return view('logviewer::admin.logviewers.index', compact('data_logs'));
    }

    private function pathFromInput($input_string)
    {
        return $this->logviewer->pathToLogFile(Crypt::decrypt($this->request->input($input_string)));
    }
}
