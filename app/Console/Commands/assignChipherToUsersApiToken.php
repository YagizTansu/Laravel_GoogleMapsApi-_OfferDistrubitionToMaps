<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class assignChipherToUsersApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignChipherToUsersApiToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::get('id');
        foreach ($users as $user) {
            $apitoken = crypto_key();
            User::where('id',$user->id)->update(['crypt_api_token'=>$apitoken]);
        }
    }

    function crypto_key()
    {
        for ($i=0; $i < 4; $i++) {
            for ($j=0; $j < 4 ; $j++) {
                $matrix[$i][$j] =bin2hex(random_bytes(1));
            }
        }

        $array = zigZagMatrix($matrix, 4, 4);

        $k = 0;
        for ($i=0; $i < 4; $i++) {
            for ($j=0; $j < 4 ; $j++) {
                $matrix2[$i][$j] =$array[$k++];
            }
        }

        return array_merge($matrix,transpose($matrix2,4,4));

    }

    function zigZagMatrix($arr,$n, $m)
    {
        $row = 0; $col = 0;

        $row_inc = false;

        $array =array();

        // Print matrix of lower half zig-zag pattern
        $mn = min($m, $n);
        for ($len = 1;$len <= $mn; $len++)
        {
            for ($i = 0;$i < $len; $i++)
            {
                $array[] = $arr[$row][$col];

                if ($i + 1 == $len)
                    break;

                if ($row_inc)
                {
                    $row++; $col--;
                }
                else
                {
                    $row--; $col++;
                }
            }

            if ($len == $mn)
                break;

            // Update row or col
            // value according
            // to the last increment
            if ($row_inc)
            {
                ++$row; $row_inc = false;
            }
            else
            {
                ++$col; $row_inc = true;
            }
        }

        // Update the indexes of
        // row and col variable
        if ($row == 0)
        {
            if ($col == $m - 1)
                ++$row;
            else
                ++$col;
            $row_inc = 1;
        }
        else
        {
            if ($row == $n - 1)
                ++$col;
            else
                ++$row;
            $row_inc = 0;
        }

        // Print the next half
        // zig-zag pattern
        $MAX = max($m, $n) - 1;
        for ($len, $diag = $MAX;
            $diag > 0; --$diag)
        {
            if ($diag > $mn)
                $len = $mn;
            else
                $len = $diag;

            for ($i = 0;
                $i < $len; ++$i)
            {
                $array[] = $arr[$row][$col];

                if ($i + 1 == $len)
                    break;

                // Update row or col
                // value according to
                // the last increment
                if ($row_inc)
                {
                    ++$row; --$col;
                }
                else
                {
                    ++$col; --$row;
                }
            }

            // Update the indexes of
            // row and col variable
            if ($row == 0 ||
                $col == $m - 1)
            {
                if ($col == $m - 1)
                    ++$row;
                else
                    ++$col;

                $row_inc = true;
            }

            else if ($col == 0 ||
                    $row == $n - 1)
            {
                if ($row == $n - 1)
                    ++$col;
                else
                    ++$row;

                $row_inc = false;
            }
        }
        return $array;
    }

    function transpose($arr,$n,$m)
    {
        for ($i = 0; $i < $n; $i++){
            for ($j = 0; $j < $m; $j++){
                $transpose[$i][$j] = $arr[$j][$i];
            }
        }
        return $transpose;
    }
}
