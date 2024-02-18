<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('source_fields', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('hiorg');
            $table->string('key');
            $table->string('name');
            $table->string('type');
            $table->boolean('needs_extra_value')->default(false);
        });

        Schema::create('destination_fields', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('fe2');
            $table->string('key');
            $table->string('name');
            $table->string('type');
        });

        \App\Models\DestinationField::forceCreate([
            'key' => 'externalDbId',
            'name' => 'externe ID',
            'type' => 'string',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'firstName',
            'name' => 'Vorname',
            'type' => 'string',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'lastName',
            'name' => 'Nachname',
            'type' => 'string',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'note',
            'name' => 'Notiz',
            'type' => 'string',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'osFunctions',
            'name' => 'OS Funktionen',
            'type' => 'array',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'osGroups',
            'name' => 'OS Gruppen',
            'type' => 'array',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'alarmGroups',
            'name' => 'Alarmgruppen',
            'type' => 'array',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'issi',
            'name' => 'ISSI',
            'type' => 'string',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'xmpp',
            'name' => 'XMPP',
            'type' => 'string',
        ]);
        \App\Models\DestinationField::forceCreate([
            'key' => 'aPagerPro',
            'name' => 'aPagerPro Adresse',
            'type' => 'string',
        ]);

        \App\Models\DestinationField::forceCreate([
            'key' => 'email',
            'name' => 'E-Mail Adresse',
            'type' => 'string',
        ]);

        \App\Models\DestinationField::forceCreate([
            'key' => 'mobil',
            'name' => 'Handynummer',
            'type' => 'string',
        ]);

        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.anrede',
            'name' => 'Anrede',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.vorname',
            'name' => 'Vorname',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.name',
            'name' => 'Vor- & Nachname',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.nachname',
            'name' => 'Nachname',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.username',
            'name' => 'Benutzername',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.telpriv',
            'name' => 'Telefon privat',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.teldienst',
            'name' => 'Telefon dienst.',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.handy',
            'name' => 'Handynummer',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.email',
            'name' => 'E-Mail',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.adresse',
            'name' => 'Straße & Hnr',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.plz',
            'name' => 'Postleitzahl',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.ort',
            'name' => 'Ort',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.land',
            'name' => 'Land',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.gebdat',
            'name' => 'Geburtsdatum',
            'type' => 'date',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.gebort',
            'name' => 'Geburtsort',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.gruppen_namen',
            'name' => 'Gruppen',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.orgakuerzel',
            'name' => 'Organisationskürzel',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.leitung',
            'name' => 'Leitungsfunktion',
            'type' => 'bool',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.status',
            'name' => 'Status',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.gesperrt',
            'name' => 'Gesperrt',
            'type' => 'bool',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.rechte',
            'name' => 'Rechte',
            'type' => 'array',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.qualifikationen',
            'name' => 'Qualifikationen',
            'type' => 'array',
            'needs_extra_value' => true,
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.fahrerlaubnis.klassen',
            'name' => 'Fahrerlaubnis Klassen',
            'type' => 'array',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.fahrerlaubnis.beschraenkung',
            'name' => 'Fahrerlaubnis Beschränkung',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.fahrerlaubnis.fuehrerscheinnummer',
            'name' => 'Führerscheinnummer',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.fahrerlaubnis.fuehrerscheindatum',
            'name' => 'Führerscheindatum',
            'type' => 'date',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.mitglied_seit',
            'name' => 'Mitglied seit',
            'type' => 'date',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.austritt_datum',
            'name' => 'Austrittsdatum',
            'type' => 'date',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.wechseljgddat',
            'name' => 'Wechseldatum aus Jugend',
            'type' => 'date',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.bemerkung',
            'name' => 'Bemerkung',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.funktion',
            'name' => 'Funktion',
            'type' => 'string',
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'attributes.benutzerdefinierte_felder',
            'name' => 'Benutzerdefiniertes Feld',
            'type' => 'array',
            'needs_extra_value' => true,
        ]);
        \App\Models\SourceField::forceCreate([
            'key' => 'id',
            'name' => 'HiOrg ID',
            'type' => 'string',
        ]);



        Schema::create('rule_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Organization::class);
            $table->string('type'); // add/replace
            $table->string('operation')->default('and');
            $table->string('name');
            $table->integer('order');
            $table->string('set_value')->nullable();
            $table->string('set_value_type')->default('text'); //text,field,Qualifikation:Name,Qualifikation:nameKurz
            $table->foreignIdFor(\App\Models\DestinationField::class)->nullable();
            $table->timestamps();
        });

        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\RuleSet::class);
            $table->foreignIdFor(\App\Models\SourceField::class)->nullable();
            $table->string('source_field_extra_name')->nullable();
            $table->string('compare_class');
            $table->boolean('not')->default(false);
            $table->string('compare_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_fields');
        Schema::dropIfExists('destination_fields');
        Schema::dropIfExists('rule_sets');
        Schema::dropIfExists('rules');
    }
};
