<?php

namespace Modules\Logviewer\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Logviewer\Services\ServiceLogViewer;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controller;

class LogviewerController extends Controller
{

    private $logviewer;
    private $request;

    public function __construct(ServiceLogViewer $logViewer, Request $request)
    {
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

        $data = [
            'logs' => $this->logviewer->all(),
            'folders' => $this->logviewer->getFolders(),
            'current_folder' => $this->logviewer->getFolderName(),
            'folder_files' => $folderFiles,
            'files' => $this->logviewer->getFiles(true),
            'current_file' => $this->logviewer->getFileName(),
            'standardFormat' => true,
        ];

        if (is_array($data['logs'])) {
            $firstLog = reset($data['logs']);
            if (!$firstLog['context'] && !$firstLog['level']) {
                $data['standardFormat'] = false;
            }
        }

        return response()->json($data);
    }
}
