import React, { useEffect, useState } from 'react';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import Fade from '@/components/elements/Fade';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFileArchive, faLevelUpAlt, faTrashAlt } from '@fortawesome/free-solid-svg-icons';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import useFileManagerSwr from '@/plugins/useFileManagerSwr';
import useFlash from '@/plugins/useFlash';
import compressFiles from '@/api/server/files/compressFiles';
import { ServerContext } from '@/state/server';
import ConfirmationModal from '@/components/elements/ConfirmationModal';
import deleteFiles from '@/api/server/files/deleteFiles';
import RenameFileModal from '@/components/server/files/RenameFileModal';

const MassActionsBar = () => {
    const uuid = ServerContext.useStoreState(state => state.server.data!.uuid);

    const { mutate } = useFileManagerSwr();
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const [ loading, setLoading ] = useState(false);
    const [ loadingMessage, setLoadingMessage ] = useState('');
    const [ showConfirm, setShowConfirm ] = useState(false);
    const [ showMove, setShowMove ] = useState(false);
    const directory = ServerContext.useStoreState(state => state.files.directory);

    const selectedFiles = ServerContext.useStoreState(state => state.files.selectedFiles);
    const setSelectedFiles = ServerContext.useStoreActions(actions => actions.files.setSelectedFiles);

    useEffect(() => {
        if (!loading) setLoadingMessage('');
    }, [ loading ]);

    const onClickCompress = () => {
        setLoading(true);
        clearFlashes('files');
        setLoadingMessage('正在压缩文件...');

        compressFiles(uuid, directory, selectedFiles)
            .then(() => mutate())
            .then(() => setSelectedFiles([]))
            .catch(error => clearAndAddHttpError({ key: 'files', error }))
            .then(() => setLoading(false));
    };

    const onClickConfirmDeletion = () => {
        setLoading(true);
        setShowConfirm(false);
        clearFlashes('files');
        setLoadingMessage('正在删除文件...');

        deleteFiles(uuid, directory, selectedFiles)
            .then(() => {
                mutate(files => files.filter(f => selectedFiles.indexOf(f.name) < 0), false);
                setSelectedFiles([]);
            })
            .catch(error => {
                mutate();
                clearAndAddHttpError({ key: 'files', error });
            })
            .then(() => setLoading(false));
    };

    return (
        <Fade timeout={75} in={selectedFiles.length > 0} unmountOnExit>
            <div css={tw`pointer-events-none fixed bottom-0 z-20 left-0 right-0 flex justify-center`}>
                <SpinnerOverlay visible={loading} size={'large'} fixed>
                    {loadingMessage}
                </SpinnerOverlay>
                <ConfirmationModal
                    visible={showConfirm}
                    title={'删除这些文件?'}
                    buttonText={'是'}
                    onConfirmed={onClickConfirmDeletion}
                    onModalDismissed={() => setShowConfirm(false)}
                >
                    你确定删除这些共计 {selectedFiles.length} 个文件?
                    <br/>
                    删除文件是一项永久性操作，无法撤销！
                    <br/>
                    <code>
                        { selectedFiles.slice(0, 15).map(file => (
                            <li key={file}>{file}<br/></li>))
                        }
                        { selectedFiles.length > 15 &&
                                    <li> + {selectedFiles.length - 15} other(s) </li>
                        }
                    </code>
                </ConfirmationModal>
                {showMove &&
                <RenameFileModal
                    files={selectedFiles}
                    visible
                    appear
                    useMoveTerminology
                    onDismissed={() => setShowMove(false)}
                />
                }
                <div css={tw`pointer-events-auto rounded p-4 mb-6`} style={{ background: 'rgba(0, 0, 0, 0.35)' }}>
                    <Button size={'xsmall'} css={tw`mr-4`} onClick={() => setShowMove(true)}>
                        <FontAwesomeIcon icon={faLevelUpAlt} css={tw`mr-2`}/> 移动
                    </Button>
                    <Button size={'xsmall'} css={tw`mr-4`} onClick={onClickCompress}>
                        <FontAwesomeIcon icon={faFileArchive} css={tw`mr-2`}/> 压缩
                    </Button>
                    <Button size={'xsmall'} color={'red'} isSecondary onClick={() => setShowConfirm(true)}>
                        <FontAwesomeIcon icon={faTrashAlt} css={tw`mr-2`}/> 删除
                    </Button>
                </div>
            </div>
        </Fade>
    );
};

export default MassActionsBar;
