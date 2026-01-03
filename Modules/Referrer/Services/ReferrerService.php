<?php

namespace Modules\Referrer\Services;

use Modules\Referrer\App\Models\Referrer;
use Modules\Referrer\Repositories\ReferrerRepository;

class ReferrerService
{
    protected ReferrerRepository $repo;

    public function __construct(ReferrerRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getReferrers(array $params)
    {
        $filters = [
            'status' => $params['status'] ?? null,
            'title' => $params['title'] ?? null,
        ];
        $perPage = $params['per_page'] ?? 15;
        $paginate = $params['paginate'] ?? true;
        return $this->repo->getReferrers($filters, $perPage, $paginate);
    }

    public function getTrashedReferrers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedReferrers();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Referrer
    {
        $referrer = $this->repo->find($id);

        if (!$referrer) {
            return null;
        }

        $this->repo->update($referrer, $data);

        return $referrer->fresh();
    }

    public function delete(int $id): bool
    {
        $referrer = $this->repo->find($id);
        if (!$referrer) {
            return false;
        }
        return $referrer->delete();
    }

    public function restoreReferrer($id)
    {
        $referrer = $this->repo->findTrashedById($id);
        if (!$referrer) {
            return false;
        }
        return $this->repo->restore($referrer);
    }

    public function forceDeleteReferrer($id)
    {
        $this->repo->forceDelete($id);
    }

}
