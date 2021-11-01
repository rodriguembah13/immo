<?php


namespace App\Form\DataTransformer;


use App\Entity\Local;
use App\Repository\LocalRepository;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

class LocalArrayToStringTransformer implements DataTransformerInterface
{
    private $localRepository;

    public function __construct(LocalRepository $localRepository)
    {
        $this->localRepository = $localRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($locals): string
    {
        // The value received is an array of Tag objects generated with
        // Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::transform()
        // The value returned is a string that concatenates the string representation of those objects

        /* @var Local[] $locals */
        return implode(',', $locals);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($string): array
    {
        if (null === $string || u($string)->isEmpty()) {
            return [];
        }

        $names = array_filter(array_unique(array_map('trim', u($string)->split(','))));

        // Get the current tags and find the new ones that should be created.
        $locals = $this->localRepository->findBy([
            'number' => $names,
        ]);
        $newNames = array_diff($names, $locals);
        foreach ($newNames as $name) {
            $local = new Local();
            $local->setNumber($name);
            $locals[] = $local;

            // There's no need to persist these new tags because Doctrine does that automatically
            // thanks to the cascade={"persist"} option in the App\Entity\Post::$tags property.
        }

        // Return an array of tags to transform them back into a Doctrine Collection.
        // See Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::reverseTransform()
        return $locals;
    }
}
