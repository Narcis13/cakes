<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Patients Model
 *
 * @property \App\Model\Table\AppointmentsTable&\Cake\ORM\Association\HasMany $Appointments
 * @method \App\Model\Entity\Patient newEmptyEntity()
 * @method \App\Model\Entity\Patient newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Patient> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Patient get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Patient findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Patient patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Patient> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Patient|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Patient saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Patient>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Patient>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Patient>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Patient> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Patient>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Patient>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Patient>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Patient> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PatientsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('patients');
        $this->setDisplayField('full_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Appointments', [
            'foreignKey' => 'patient_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('full_name')
            ->maxLength('full_name', 100)
            ->requirePresence('full_name', 'create')
            ->notEmptyString('full_name');

        $validator
            ->email('email')
            ->maxLength('email', 255)
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('phone')
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone')
            ->add('phone', 'validRomanianMobile', [
                'rule' => ['custom', '/^07[0-9]{8}$/'],
                'message' => 'Numărul de telefon trebuie să aibă 10 cifre și să înceapă cu 07 (ex: 0722123321).',
            ]);

        $validator
            ->scalar('password')
            ->minLength('password', 8, 'Parola trebuie să aibă minim 8 caractere.')
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

        $validator
            ->boolean('orizont_extins_programare')
            ->allowEmptyString('orizont_extins_programare');

        $validator
            ->dateTime('email_verified_at')
            ->allowEmptyDateTime('email_verified_at');

        $validator
            ->scalar('verification_token')
            ->maxLength('verification_token', 64)
            ->allowEmptyString('verification_token');

        $validator
            ->scalar('password_reset_token')
            ->maxLength('password_reset_token', 64)
            ->allowEmptyString('password_reset_token');

        $validator
            ->dateTime('password_reset_expires')
            ->allowEmptyDateTime('password_reset_expires');

        $validator
            ->integer('failed_login_attempts')
            ->notEmptyString('failed_login_attempts');

        $validator
            ->dateTime('locked_until')
            ->allowEmptyDateTime('locked_until');

        $validator
            ->dateTime('last_login_at')
            ->allowEmptyDateTime('last_login_at');

        $validator
            ->scalar('last_login_ip')
            ->maxLength('last_login_ip', 45)
            ->allowEmptyString('last_login_ip');

        return $validator;
    }

    /**
     * Admin validation rules.
     *
     * Only validates fields editable by admin (full_name, phone).
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAdmin(Validator $validator): Validator
    {
        $validator
            ->scalar('full_name')
            ->maxLength('full_name', 100)
            ->notEmptyString('full_name');

        $validator
            ->scalar('phone')
            ->notEmptyString('phone')
            ->add('phone', 'validRomanianMobile', [
                'rule' => ['custom', '/^07[0-9]{8}$/'],
                'message' => 'Numărul de telefon trebuie să aibă 10 cifre și să înceapă cu 07.',
            ]);

        $validator
            ->boolean('orizont_extins_programare');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email'], 'Această adresă de email este deja înregistrată.'));

        return $rules;
    }

    /**
     * Find active and verified patients only
     * Used by authentication to ensure only verified patients can log in
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to modify
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where([
            $this->aliasField('is_active') => true,
            $this->aliasField('email_verified_at') . ' IS NOT' => null,
        ]);
    }

    /**
     * Find patient by verification token
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to modify
     * @param string $token The verification token
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByVerificationToken(SelectQuery $query, string $token): SelectQuery
    {
        return $query->where([
            $this->aliasField('verification_token') => $token,
        ]);
    }

    /**
     * Find patient by password reset token (valid only)
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to modify
     * @param string $token The password reset token
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByPasswordResetToken(SelectQuery $query, string $token): SelectQuery
    {
        return $query->where([
            $this->aliasField('password_reset_token') => $token,
            $this->aliasField('password_reset_expires') . ' >=' => new DateTime(),
        ]);
    }
}
