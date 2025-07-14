<?php

namespace App\Imports;

use App\Models\NcageRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NcageRecordsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if (empty($row['ncage'])) {
                continue;
            }

            NcageRecord::updateOrCreate(
                [
                    // Kolom unik untuk mencari data
                    'ncage_code' => $row['ncage']
                ],
                [
                    // Data untuk di-update atau dibuat baru
                    'ncagesd'           => $row['ncagesd'],
                    'toec'              => $row['toec'],
                    'entity_name'       => $row['entity_name'],
                    'street'            => $row['street_st12'],
                    'city'              => $row['city_cit'],
                    'psc'               => $row['post_code_physical_address_psc'],
                    'country'           => $row['country'],
                    'ctr'               => $row['iso_ctr'],
                    'stt'               => $row['stateprovince_stt'],
                    'ste'               => $row['fips_state_ste'],
                    'is_sam_requested'  => filter_var($row['cage_code_requested_for_sam'], FILTER_VALIDATE_BOOLEAN),
                    'remarks'           => $row['remarks'],
                    'last_change_date_international' => $row['date_last_change_international'],
                    'change_date'       => $row['change_date'],
                    'creation_date'     => $row['creation_date'],
                    'load_date'         => $row['load_date'],
                    'national'          => $row['national'],
                    'nac'               => $row['nac'],
                    'idn'               => $row['idn'],
                    'bar'               => $row['bar'],
                    'nai'               => $row['nai'],
                    'cpv'               => $row['cpv'],
                    'uns'               => $row['uns'],
                    'sic'               => $row['sic'],
                    'tel'               => $row['voice_telephone_number_tel'],
                    'fax'               => $row['telefax_number_fax'],
                    'ema'               => $row['email_ema'],
                    'www'               => $row['www_www'],
                    'pob'               => $row['post_office_box_number_pob'],
                    'pcc'               => $row['city_postal_address_pcc'],
                    'pcs'               => $row['post_code_postal_address_pcs'],
                    'rp1_5'             => $row['replaced_by_rp15'],
                    'nmcrl_ref_count'   => $row['nmcrl_reference_count'] ? (int)$row['nmcrl_reference_count'] : null,
                ]
            );
        }
    }
}
