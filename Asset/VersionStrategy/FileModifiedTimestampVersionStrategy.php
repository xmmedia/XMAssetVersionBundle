<?php

/*
 * (c) XM Media Inc. <dhein@xmmedia.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace XM\AssetVersionBundle\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Versions the file based on the file modified timestamp.
 *
 * @author Darryl Hein, XM Media Inc. <dhein@xmmedia.com>
 */
class FileModifiedTimestampVersionStrategy implements VersionStrategyInterface
{
	/**
	 * The path to the root of the web directory.
	 * Used to find the assets.
	 * @var string
	 */
	protected $webRoot;

	/**
	 * TimestampVersionStrategy constructor.
	 *
	 * @param string $webRoot The path to the root of the web directory.
	 */
	public function __construct($webRoot)
	{
		$this->webRoot = $webRoot;
	}

	/**
	 * Returns the asset version for an asset.
	 * Uses the file modified time if the file exists,
	 * otherwise the current timestamp.
	 *
	 * @param string $path A path
	 *
	 * @return string The version string
	 */
	public function getVersion($path)
	{
		$fullPath = realpath($this->webRoot.'/'.$path);

		if (false === $fullPath) {
			return time();
		}

		return filemtime($fullPath);
	}

	/**
	 * Applies version to the supplied path.
	 * The path format is: `version-1242354/path/to/file.ext
	 *
	 * @param string $path A path
	 *
	 * @return string The versionized path
	 */
	public function applyVersion($path)
	{
		return sprintf('version-%s/%s', $this->getVersion($path), $path);
	}
}