<?php
/**
 * src/Traits/EncryptedAttribute.php.
 * D:\PROGETTI\LARAVEL\auth-app\app\RainbowTable\RainbowTrait.php
 */
namespace App\RainbowTable;

// use ESolution\DBEncryption\Builders\EncryptionEloquentBuilder;
use App\RainbowTable\RainbowEloquentBuilder;
// use ESolution\DBEncryption\Encrypter;
use App\RainbowTable\Encrypter;
use Illuminate\Support\Facades\Log;

trait Rainbow_old_Trait {

    public static $enableEncryption = true;

    function __construct() {
      self::$enableEncryption = config('laravelDatabaseEncryption.enable_encryption');
    }


    public static function showInfo()
    {
        Log::debug('RainbowTrait!', [] );
    }

    public function encrypter()
    {
        /*
        if(! $this->magrosEncrypter){
            $this->magrosEncrypter = new Encrypter();
        }
        */
        return "ENCRYTPTER OK!";
    }


     /**
     * @param $key
     * @return bool
     */
    public function isEncryptable($key)
    {
        Log::debug('RainbowTrait!isEncryptable', [$key] );
        if(self::$enableEncryption){
            return in_array($key, $this->encryptable);
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getEncryptableAttributes()
    {
        return $this->encryptable;
    }

    public function getAttribute($key)
    {
      // Log::debug('RainbowTrait!getAttribute', [$key] );
      $value = parent::getAttribute($key);
      if ($this->isEncryptable($key) && (!is_null($value) && $value != ''))
      {
        try {
          $value = Encrypter::decrypt($value);
        } catch (\Exception $th) {}
      }
      return $value;
    }

    public function setAttribute($key, $value)
    {
      // Log::debug('RainbowTrait!setAttribute', [$key] );
      if ($this->isEncryptable($key) && (!is_null($value) && $value != ''))
      {
        try {
          $value = Encrypter::encrypt($value);
        } catch (\Exception $th) {
            dd($th);
        }
      }
      return parent::setAttribute($key, $value);
    }

    public function attributesToArray()
    {
        
        $attributes = parent::attributesToArray();
        // Log::debug('RainbowTrait!attributesToArray', [$attributes] );
        if ($attributes) {
          foreach ($attributes as $key => $value)
          {
            if ($this->isEncryptable($key) && (!is_null($value)) && $value != '')
            {
              $attributes[$key] = $value;
              try {
                $attributes[$key] = Encrypter::decrypt($value);
              } catch (\Exception $th) {
                dd($th);
              }
            }
          }
        }
        return $attributes;
    }

    // Extend EncryptionEloquentBuilder
    
    public function newEloquentBuilder($query)
    {
        Log::debug('RainbowTrait!newEloquentBuilder', [$query] );
        return new RainbowEloquentBuilder($query);
    }
    

    /**
     * Decrypt Attribute
     *
     * @param string $value
     *
     * @return string
     */
    public function decryptAttribute($value)
    {
       return (!is_null($value) && $value != '') ? Encrypter::decrypt($value) : $value;
    }

    /**
     * Encrypt Attribute
     *
     * @param string $value
     *
     * @return string
     */
    public function encryptAttribute($value)
    {
        return (!is_null($value) && $value != '') ? Encrypter::encrypt($value) : $value;
    }
}