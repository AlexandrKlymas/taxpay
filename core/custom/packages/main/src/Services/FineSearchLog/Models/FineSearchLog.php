<?php


namespace EvolutionCMS\Main\Services\FineSearchLog\Models;


use Carbon\Carbon;

/**
 * EvolutionCMS\Main\Services\FineSearchLog\Models\FineSearchLog
 *
 * @property int $id
 * @property string $license_plate
 * @property string|null $tax_number
 * @property string|null $tech_passport
 * @property string|null $driving_license
 * @property \Illuminate\Support\Carbon|null $driving_license_date_issue
 * @property string|null $fine_series
 * @property string|null $fine_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereDrivingLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereDrivingLicenseDateIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereFineNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereFineSeries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereLicensePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereTechPassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FineSearchLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FineSearchLog extends \Eloquent
{

    protected $dates = [
        'driving_license_date_issue',
        'created_at',
        'updated_at',
    ];
    
    protected $fillable = [
        'license_plate',
        'tax_number',
        'tech_passport',
        'driving_license',
        'driving_license_date_issue',
        'fine_series',
        'fine_number',
    ];

}