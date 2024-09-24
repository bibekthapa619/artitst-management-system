<?php

namespace App\Exports;

use App\Services\ArtistService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class ArtistExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected $artistService;
    protected $superAdminId;
    protected $search;

    /**
     * Constructor to initialize the ArtistService and filters.
     *
     * @param ArtistService $artistService
     * @param string $superAdminId
     * @param string|null $search
     */
    public function __construct(ArtistService $artistService, $superAdminId, $search = null)
    {
        $this->artistService = $artistService;
        $this->superAdminId = $superAdminId;
        $this->search = $search;
    }

    /**
     * Retrieve the collection of artists to be exported
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Prepare the condition and bindings
        $condition = "users.super_admin_id ='$this->superAdminId'";
        $bindings = [];

        if ($this->search) {
            $condition .= " AND (CONCAT(users.first_name, ' ', users.last_name) like ?
                            OR artists.name like ?
                            OR users.email like ? 
                            OR users.phone like ? 
                        )";
            $bindings = [
                "%{$this->search}%",
                "%{$this->search}%",
                "%{$this->search}%",
                "%{$this->search}%",
            ];
        }

        // Use the ArtistService to fetch the required data
        $data = $this->artistService->getAllArtists(
            columns: [
                'users.first_name',
                'users.last_name',
                'users.dob',
                'users.gender',
                'users.phone',
                'users.address',
                'users.email',
                'artists.name as artist_name',
                'artists.first_release_year',
                'artists.no_of_albums_released'
            ],
            condition: $condition,
            bindings: $bindings,
            orderBy: 'artists.id ASC',
            pageSize: null,
            currentPage: null
        );

        return collect($data); // Return as a collection for export
    }

    /**
     * Define the headings for the CSV file
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Date of Birth',
            'Gender',
            'Phone',
            'Address',
            'Email',
            'Artist Name',
            'First Release Year',
            'Number of Albums Released'
        ];
    }

    /**
     * Map the data to match the CSV columns
     *
     * @param $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row['first_name'],
            $row['last_name'],
            $row['dob'],
            $row['gender'],
            $row['phone'],
            $row['address'],
            $row['email'],
            $row['artist_name'],
            $row['first_release_year'],
            $row['no_of_albums_released'],
        ];
    }
}
