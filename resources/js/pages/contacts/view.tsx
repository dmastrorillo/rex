import { ContactActionsBar } from '@/components/contacts/contact/contact-actions-bar';
import { ContactDetails } from '@/components/contacts/contact/contact-details';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type Contact } from '@/types';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Contacts',
        href: '/contacts',
    },
];

type ContactViewProps = {
    contact: Contact;
};

export default function ContactView({ contact }: ContactViewProps) {
    const [isDeleting, setIsDeleting] = useState(false);
    const [isEditing, setIsEditing] = useState(false);

    const handleDelete = () => {
        if (confirm('Are you sure you want to delete this contact?')) {
            setIsDeleting(true);
            router.delete(`/contacts/${contact.id}`, {
                onFinish: () => setIsDeleting(false),
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs.concat({ title: `${contact.firstName} ${contact.surname}`, href: `/contacts/${contact.id}` })}>
            <Head title={`Contact - ${contact.firstName} ${contact.surname}`} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 overflow-hidden rounded-xl border p-6">
                    <ContactActionsBar
                        isEditing={isEditing}
                        handleEdit={() => setIsEditing(true)}
                        isDeleting={isDeleting}
                        handleDelete={handleDelete}
                    />
                    <ContactDetails contact={contact} isEditing={isEditing} cancelEdit={() => setIsEditing(false)} />
                </div>
            </div>
        </AppLayout>
    );
}
