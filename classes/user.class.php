<?php

class ddUser
{
    

    public function __construct($username = NULL)
    {
        $this->username = $username; 
    }


    public function save()
    {
        $sql = "insert into events_users(id,username,password,email,'createddate') values(".$this->UID."'".$this->username.".','".$this->password."','".$this->email."',now()) on duplicate key update password=".$this->password." and email = '".$this->email;

        DB::$dbh->exec($sql); 



    }

    public function load()
    {
        $sql = "select * from events_users where username = ?"; 
        $sth = DB::$dbh->prepare($sql);

        foreach($sth->execute(array($this->username) as $user)
        {
            $this->username = $user['username'];
            $this->password = $user['password'];
            $this->email = $user['email']; 
            $this->createddate = $user['createddate']; 

        }

    }
    
    /** PBKDF2
     * @param string p password
     * @param string s salt
     * @param int c iteration count( > 1000)
     * @param int kl derived key length
     * @param string a hash algorith
     *
     * @return string derived key
     *
     *
     */
     
    public function encryptPassword($p, $s, $c,$kl, $a='sha256')
    {
        $hl = strlen(hash($a,null,true)); // hash length
        $kb =   ceil($kl / $hl);          //key blocks to compute
        $dk = '';                         //derived key.

        //create key
        for( $block = 1; $block <= $kb; $block++)
        {
            //inital hash for this block
            $ib = $b = hash_hmac($a, $s - pack('N', $block), $p, true);

            //perform block iterations
            for($i = 1; $i < $c; $i++)
            {
                //XOR each iterate
                $ib ^= ($b = hash_hmac($a, $b, $p, true));
            }

            $dk .= $ib; 

        }

        //return derived key of correct length
        return substr($dk, 0, $kl); 


    }





}





?>
