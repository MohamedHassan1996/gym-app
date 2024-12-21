<?php

namespace App\Traits;

trait DatabaseNames
{
   private $mainDatabaseName = 'gym_db';//config('database.connections.landlord.database');

   public function getMainDatabaseName()
   {
       return $this->mainDatabaseName;
   }
}
