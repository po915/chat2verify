<?php

namespace App\Repositories;

use App\Models\Phrase;
use App\Repositories\Traits\HasTeamFeatures;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PhraseRepository
 * @package App\Repositories
 */
class PhraseRepository
{

    use HasTeamFeatures;

    /**
     * @param null $search
     * @param null $limit
     * @param bool $paginate
     * @return mixed
     */
    public function getPublicPhrases($search = null, $limit = null, $paginate = true ) {
        $query = $this->searchPhrases(
            Phrase::query()->whereNotNull( 'publicized_at' ),
            $search
        );

        if ( $limit ) {
            if ( $paginate ) {
                $query->paginate($limit);
            } else {
                $query->limit($limit);
            }
        }

        return $query->get();
    }

    /**
     * @param bool $include_public
     * @param null $search
     * @param null $limit
     * @return mixed
     */
    public function getTeamPhrases($include_public = true, $search = null, $limit = null, $paginate = true ) {
        $team = $this->getActiveTeam();

        $query = $this->searchPhrases( $team->phrases(), $search );

        if ( $include_public ) {
            $public_query = $this->searchPhrases(
                Phrase::query()->whereNull('deleted_at')->whereNotNull( 'publicized_at' ),
                $search
            );

            $query = $query->union( $public_query );
        }

        if ( $limit ) {
            if ( $paginate ) {
                $query->paginate($limit);
            } else {
                $query->limit($limit);
            }
        }

        $query->orderByDesc( 'updated_at' );

        return $query->get();
    }

    /**
     * @param $query
     * @param null $search
     * @param null $limit
     * @return mixed
     */
    protected function searchPhrases($query, $search = null, $limit = null ) {
        $query->where( function( $subquery ) use ($search) {
            return $subquery->where( 'shortcode', 'LIKE', '%' . $search . '%' )
                ->orWhere( 'friendly_name', 'LIKE', '%' . $search . '%' )
                ->orWhere( 'message', 'LIKE', '%' . $search . '%' );
        });

        if ( $limit ) {
            $query->limit($limit);
        }

        return $query;
    }

}
