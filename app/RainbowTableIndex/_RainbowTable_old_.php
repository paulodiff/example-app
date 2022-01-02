<?php

namespace App\RainbowTable;

class RainbowTable
{

    public $RAINBOW_TABLE = array();
    protected $MIN_WORD_LEN;
    protected $STRING_SEPARATOR;
    public $debug = false;

    public function __construct($mwl = 3, $sp = ";") {
        // echo "RainbowTable build mwl:" . $mwl . " sp:" . $sp .  "\n"; 
        $this->MIN_WORD_LEN = $mwl;
        $this->STRING_SEPARATOR = $sp;
    }

    // method declaration

    public function sanitize_string($s)
    {
      // $s = filter_var($s, 	FILTER_SANITIZE_STRING,     FILTER_FLAG_STRIP_HIGH);
      $s = str_replace(['?' , '!' , '-' , ';' , ' '], '', $s);
      //$s = strtoupper($s);
      return $s;
    }

    public function add($index, $s)
    {
  
      // echo "RainbowTable add: ($index)" . $s . "\n"; 

      $s = $this->sanitize_string($s);
      $pieces = explode(" ", $s);
      // print_r($pieces);

      foreach ($pieces as $key=>$value) 
      { 
        // echo "## analyze; ", $key , " - (" , $value, ")\n";
        $str_len = strlen($value);
        $token_len = 3;

        if( $str_len >= $this->MIN_WORD_LEN )
        {
          for($token_len = 3; $token_len <= $str_len; $token_len++)
          {
            for($start = 0; $start <= ($str_len - $token_len); $start++) 
            {
              // echo "p tl:$token_len strlen:$str_len start:$start\n";
              $t = mb_substr($value, $start, $token_len);
              // echo "#", $key , ")" , $value, ":", $start, ":", $token_len, ":", $t, "\n";  

              // if (array_key_exists('first', $search_array))
              // se esiste la chiave ....
              if (array_key_exists($t, $this->RAINBOW_TABLE))
              {
                $a = $this->RAINBOW_TABLE[$t];
                $a1 = explode($this->STRING_SEPARATOR, $a);

                // print_r($a1);

                // se l'indice non esiste lo inserische
                if (!in_array($index, $a1)) 
                {
                  array_push($a1, $index);
                  $this->RAINBOW_TABLE[$t] = implode($this->STRING_SEPARATOR, $a1);
                }
              }
              else
              {
                $this->RAINBOW_TABLE[$t] = $index;
              }
            }
          }
        }
        else
        {
          // echo "SKIP:", $value, "\n";
        }
      }
    }

    function search($multiple_token_string)
    {
      // echo "RainbowTable search for:" . $multiple_token_string . "\n";
      $multiple_token_string = $this->sanitize_string($multiple_token_string);
      $pieces = explode(" ", $multiple_token_string);
      // print_r($pieces);

      $p_results = [];
      foreach ($pieces as $key=>$t) 
      {
          if (array_key_exists($t, $this->RAINBOW_TABLE))
          {
            // echo "search_in_rainbow:" . $t . " " .  $this->RAINBOW_TABLE[$t]  .  "\n";
            $a1 = explode($this->STRING_SEPARATOR, $this->RAINBOW_TABLE[$t]);
            $p_results = array_merge($p_results, $a1);
            // print_r($p_results);
          }
      }

      // print_r($p_results);
      $u = array_unique($p_results, SORT_STRING);
      // echo "RainbowTable search result for :" . $multiple_token_string . "\n";
      // print_r($u);
      return $u;
    }


    public function displayTable()
    {
      // echo "RainbowTable ------------------------- \n";
      // print_r($this->RAINBOW_TABLE);
    }

    public function getTableInfo()
    {
      $info = [];
      $info[] = "RainbowTable info:\n";
      $info[] = "N. count ". count($this->RAINBOW_TABLE) .  "\n";

      $max_len = 0;
      $item = "";
      $k = "";
      foreach ($this->RAINBOW_TABLE as $key=>$value)
      {
        $cur_len = strlen($value);
        if ($cur_len > $max_len)
        { 
          $max_len = $cur_len;
          $item = $value;
          $k = $key;
        }
      }
      $info[] = "Max item len ". $max_len .  "\n";
      $info[] = "($k) " . $item .  "\n";
      return $info;
    }

    public function displayTableInfo()
    {
      echo "RainbowTable info:\n";
      echo "N. count ". count($this->RAINBOW_TABLE) .  "\n";

      $max_len = 0;
      $item = "";
      $k = "";
      foreach ($this->RAINBOW_TABLE as $key=>$value)
      {
        $cur_len = strlen($value);
        if ($cur_len > $max_len)
        { 
          $max_len = $cur_len;
          $item = $value;
          $k = $key;
        }
      }
      echo "Max item len ". $max_len .  "\n";
      echo "($k) " . $item .  "\n";
    }

    public function displayVar() {
        // echo $this->var;
    }


  
    

}
