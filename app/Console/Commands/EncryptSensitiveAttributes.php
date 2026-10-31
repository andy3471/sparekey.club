<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EncryptSensitiveAttributes extends Command
{
    protected $signature = 'security:encrypt-sensitive-attributes';

    protected $description = 'Encrypt plaintext key codes and Discord webhook URLs at rest';

    public function handle(): int
    {
        $this->encryptColumn('keys', 'key');
        $this->encryptColumn('groups', 'discord_webhook_url', nullable: true);

        $this->info('Sensitive attributes encrypted.');

        return self::SUCCESS;
    }

    private function encryptColumn(string $table, string $column, bool $nullable = false): void
    {
        $encrypted = 0;

        DB::table($table)->orderBy('id')->chunkById(100, function ($rows) use ($table, $column, $nullable, &$encrypted): void {
            foreach ($rows as $row) {
                $value = $row->{$column};

                if ($nullable && ($value === null || $value === '')) {
                    continue;
                }

                if (! is_string($value) || $value === '') {
                    continue;
                }

                try {
                    Crypt::decryptString($value);

                    continue;
                } catch (DecryptException) {
                    DB::table($table)->where('id', $row->id)->update([
                        $column => Crypt::encryptString($value),
                    ]);
                    $encrypted++;
                }
            }
        });

        $this->line("Encrypted {$encrypted} row(s) in {$table}.{$column}");
    }
}
