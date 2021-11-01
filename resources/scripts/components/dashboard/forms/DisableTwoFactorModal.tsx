import React, { useContext } from 'react';
import { Form, Formik, FormikHelpers } from 'formik';
import FlashMessageRender from '@/components/FlashMessageRender';
import Field from '@/components/elements/Field';
import { object, string } from 'yup';
import { Actions, useStoreActions } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import disableAccountTwoFactor from '@/api/account/disableAccountTwoFactor';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import asModal from '@/hoc/asModal';
import ModalContext from '@/context/ModalContext';

interface Values {
    password: string;
}

const DisableTwoFactorModal = () => {
    const { dismiss, setPropOverrides } = useContext(ModalContext);
    const { clearAndAddHttpError } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);
    const updateUserData = useStoreActions((actions: Actions<ApplicationStore>) => actions.user.updateUserData);

    const submit = ({ password }: Values, { setSubmitting }: FormikHelpers<Values>) => {
        setPropOverrides({ showSpinnerOverlay: true, dismissable: false });
        disableAccountTwoFactor(password)
            .then(() => {
                updateUserData({ useTotp: false });
                dismiss();
            })
            .catch(error => {
                console.error(error);

                clearAndAddHttpError({ error, key: 'account:two-factor' });
                setSubmitting(false);
                setPropOverrides(null);
            });
    };

    return (
        <Formik
            onSubmit={submit}
            initialValues={{
                password: '',
            }}
            validationSchema={object().shape({
                password: string().required('您必须提供当前密码才能继续。'),
            })}
        >
            {({ isValid }) => (
                <Form className={'mb-0'}>
                    <FlashMessageRender css={tw`mb-6`} byKey={'account:two-factor'}/>
                    <Field
                        id={'password'}
                        name={'password'}
                        type={'password'}
                        label={'当前密码'}
                        description={'为了禁用双因素身份验证，您需要提供您的帐户密码。'}
                        autoFocus
                    />
                    <div css={tw`mt-6 text-right`}>
                        <Button color={'red'} disabled={!isValid}>
                            关闭双因素身份验证
                        </Button>
                    </div>
                </Form>
            )}
        </Formik>
    );
};

export default asModal()(DisableTwoFactorModal);
