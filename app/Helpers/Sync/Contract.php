<?php

namespace App\Helpers\Sync;

interface Contract
{
    public function getPhoneNumber($record);

    public function isValid($record);

    public function getGroups($record);

    public function getFunctions($record);

    public function getDataFromRecord($record);
}
