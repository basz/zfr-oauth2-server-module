<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfrOAuth2ModuleTest\Server\Options;

use ZfrOAuth2\Server\Grant\ClientCredentialsGrant;
use ZfrOAuth2Module\Server\Options\ModuleOptions;

/**
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @licence MIT
 *
 * @covers ZfrOAuth2Module\Server\Options\ModuleOptions
 */
class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testSettersAndGetters()
    {
        $callable = function() {};

        $options = new ModuleOptions([
            'object_manager'         => 'my_object_manager',
            'authorization_code_ttl' => 300,
            'access_token_ttl'       => 3000,
            'refresh_token_ttl'      => 30000,
            'owner_callable'         => $callable,
            'grants'                 => [ClientCredentialsGrant::class]
        ]);

        $this->assertEquals('my_object_manager', $options->getObjectManager());
        $this->assertEquals(300, $options->getAuthorizationCodeTtl());
        $this->assertEquals(3000, $options->getAccessTokenTtl());
        $this->assertEquals(30000, $options->getRefreshTokenTtl());
        $this->assertSame($callable, $options->getOwnerCallable());
        $this->assertEquals([ClientCredentialsGrant::class], $options->getGrants());
    }
}
