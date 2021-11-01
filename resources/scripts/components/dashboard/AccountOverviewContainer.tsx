import * as React from 'react';
import ContentBox from '@/components/elements/ContentBox';
import UpdatePasswordForm from '@/components/dashboard/forms/UpdatePasswordForm';
import UpdateEmailAddressForm from '@/components/dashboard/forms/UpdateEmailAddressForm';
import ConfigureTwoFactorForm from '@/components/dashboard/forms/ConfigureTwoFactorForm';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import { breakpoint } from '@/theme';
import styled from 'styled-components/macro';
import MessageBox from '@/components/MessageBox';
import { useLocation } from 'react-router-dom';

const Container = styled.div`
    ${tw`flex flex-wrap`};

    & > div {
        ${tw`w-full`};

        ${breakpoint('md')`
            width: calc(50% - 1rem);
        `}

        ${breakpoint('xl')`
            ${tw`w-auto flex-1`};
        `}
    }
`;

export default () => {
    const { state } = useLocation<undefined | { twoFactorRedirect?: boolean }>();

    return (
        <PageContentBlock title={'账户信息'}>
            {state?.twoFactorRedirect &&
            <MessageBox title={'需要双因素验证'} type={'error'}>
                您的帐户必须启用双因素身份验证才能继续使用。
            </MessageBox>
            }
            <Container css={[ tw`mb-10`, state?.twoFactorRedirect ? tw`mt-4` : tw`mt-10` ]}>
                <ContentBox title={'更新密码'} showFlashes={'account:password'}>
                    <UpdatePasswordForm/>
                </ContentBox>
                <ContentBox
                    css={tw`mt-8 md:mt-0 md:ml-8`}
                    title={'更新邮件地址'}
                    showFlashes={'account:email'}
                >
                    <UpdateEmailAddressForm/>
                </ContentBox>
                <ContentBox css={tw`xl:ml-8 mt-8 xl:mt-0`} title={'配置双因素验证'}>
                    <ConfigureTwoFactorForm/>
                </ContentBox>
            </Container>
        </PageContentBlock>
    );
};
