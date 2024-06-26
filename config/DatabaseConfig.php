<?php

class DatabaseConfig
{
    
    /**
     * Get config of database
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'dsn' => 'pgsql:host=172.20.0.10;dbname=business_trip_manager_db',
            'username' => 'admin',
            'password' => 'admin'
        ];
    }
}