<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class AuditService {
    protected $user;

    /**
     * init
     *
     * Initialize properties with data pulled from the logged-in user
     *
     * @return void
     */
    public function init()
    {
        $success = false;
        $this->user = Auth::user();
        if (!is_null($this->user)) {
            $success = true;
        }
        return $success;
    }

    /**
     * Write changes to the audit log
     *
     * @param $changes
     * @param $table_name
     * @param $key_field
     * @param $key_value
     * @param $user_id
     *
     * @return bool
     */
    public function writeAuditLog($changes, $table_name, $key_field, $key_value)
    {
        if ($this->init()) {
            foreach ($changes as $field => $change) {
                try{
                    $audit = new AuditLog();
                    $audit->ChangeDate = date('Y-m-d H:i:s', time());
                    $audit->TableName = $table_name;
                    $audit->KeyField = $key_field;
                    $audit->KeyValue = $key_value;
                    $audit->FieldName = $field;
                    $audit->ChangeFrom = $change['from'];
                    $audit->ChangeTo = $change['to'];
                    $audit->UserID = $this->user->id;
                    $audit->Application = config('app.name') . ' ' . config('app.version');

                    $audit->save();
                }
                catch(Exception $e){
                    #TODO: Set up error logging
                    // do task when error
                    echo $e->getMessage();   // insert query
                }
            }
        }
    }

}