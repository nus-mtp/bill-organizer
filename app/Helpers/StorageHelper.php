<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Record;
use App\RecordIssuer;

class StorageHelper
{
    // String format for RECORD_PATH = "record_issuers/{$record_issuer->id}/records"
    /**
     * RECORD_DIR_PATH = "record_issuers/{$record_issuer->id}/records"
     * RECORD_IMAGES_DIR_PATH = "record_issuers/{$record->issuer->id}/records/{$record->id}"
     */
    const RECORDS_DIR_PATH = "record_issuers/%s/records";
    const SPECIFIC_RECORD_DIR_PATH = "record_issuers/%s/records/%s";

    public static function storeUploadedRecordFile(UploadedFile $uploadedFile, RecordIssuer $recordIssuer)
    {
        return $uploadedFile->store(sprintf(StorageHelper::RECORDS_DIR_PATH, $recordIssuer->id));
    }

    /**
     * Create the img/ directory for a record if it hasn't existed
     */
    public static function createRecordImagesDir(Record $record)
    {
        $formattedPath = sprintf(StorageHelper::SPECIFIC_RECORD_DIR_PATH, $record->issuer->id, $record->id) . '/img/';
        if(!Storage::exists($formattedPath)) {
            Storage::makeDirectory($formattedPath, 0777, true, true);
        }
        return $formattedPath;
    }

    /**
     * Delete record's file and its directory (should be called only when deleting a record)
     */
    public static function deleteRecordFiles(Record $record)
    {
        if(Storage::exists($record->path_to_file)) {
            Storage::delete($record->path_to_file);
        }

        $formattedPath = sprintf(StorageHelper::SPECIFIC_RECORD_DIR_PATH, $record->issuer->id, $record->id);
        if(Storage::exists($formattedPath)) {
            Storage::deleteDirectory($formattedPath);
        }
    }

    public static function getAbsolutePath($path) {
        return storage_path('app/' . $path);
    }
}