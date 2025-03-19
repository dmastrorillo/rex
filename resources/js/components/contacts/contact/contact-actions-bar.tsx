import { Button } from '@/components/ui/button';
import { Icon } from '@/components/ui/icon';
import { ArrowLeftCircle } from 'lucide-react';

export type ActionsBarProps = {
    isDeleting: boolean;
    handleDelete: () => void;
    isEditing: boolean;
    handleEdit: () => void;
};

export function ContactActionsBar({ isDeleting, handleDelete, isEditing, handleEdit }: ActionsBarProps) {
    return (
        <div className="mb-6 flex items-center justify-between">
            <div className="flex flex-row items-center gap-2">
                <Button variant="secondary" onClick={() => history.back()}>
                    <Icon iconNode={ArrowLeftCircle} />
                </Button>

                <h1 className="text-2xl font-semibold">Contact Details</h1>
            </div>

            <div className="flex gap-2">
                {!isEditing && (
                    <>
                        <Button variant="outline" onClick={handleEdit}>
                            Edit Contact
                        </Button>

                        <Button variant="destructive" onClick={handleDelete} disabled={isDeleting}>
                            {isDeleting ? 'Deleting...' : 'Delete Contact'}
                        </Button>
                    </>
                )}
            </div>
        </div>
    );
}
