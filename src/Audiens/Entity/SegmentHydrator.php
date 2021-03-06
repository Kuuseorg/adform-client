<?php declare(strict_types=1);

namespace Audiens\AdForm\Entity;

use Audiens\AdForm\Enum\SegmentStatus;
use stdClass;

/**
 * Class SegmentHydrator
 */
class SegmentHydrator extends Segment
{
    /**
     * Hydrate a segment from a stdClass, intended to be used for
     * instancing a segment from \json_decode()
     *
     * @param stdClass $stdClass
     *
     * @return Segment
     */
    public static function fromStdClass(stdClass $stdClass): Segment
    {
        $segment = new Segment();

        $segment->id = (int) $stdClass->id;
        $segment->dataProviderId = (int)$stdClass->dataProviderId;
        $segment->status = new SegmentStatus($stdClass->status);
        $segment->categoryId = (int)$stdClass->categoryId;
        $segment->refId = $stdClass->refId;
        $segment->fee = (float)$stdClass->fee;
        $segment->ttl = (int)$stdClass->ttl;
        $segment->name = $stdClass->name;

        // might not be set in JSON
        if (isset($stdClass->formula)) {
            $segment->formula = $stdClass->formula;
        }

        // might not be set in JSON
        if (isset($stdClass->extractionRule)) {
            $segment->extractionRule = $stdClass->extractionRule;
        }

        $segment->audience = (bool)$stdClass->audience;
        $segment->audienceBySources = $stdClass->audienceBySources;
        $segment->audienceByUserIdentityType = $stdClass->audienceByUserIdentityType;
        $segment->isExtended = (bool)$stdClass->isExtended;

        $segment->frequency = (int)$stdClass->frequency;
        $segment->isCrossDevice = (bool)$stdClass->isCrossDevice;
        $segment->hasDataUsagePermissions = (bool)$stdClass->hasDataUsagePermissions;

        $segment->updatedAt = DateParser::parse($stdClass->updatedAt);
        $segment->createdAt = DateParser::parse($stdClass->createdAt);

        if (isset($stdClass->unifiedTaxonomyLabelsIds)) {
            $segment->unifiedTaxonomyLabelIds = $stdClass->unifiedTaxonomyLabelsIds;
        }

        return $segment;
    }
}
