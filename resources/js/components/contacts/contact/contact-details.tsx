import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Contact } from '@/types';
import { useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';
import { ContactForm } from '../types';

export type ContactDetailsProps = {
    contact: Contact;
    isEditing: boolean;
    cancelEdit: () => void;
};

export function ContactDetails({ contact, isEditing, cancelEdit }: ContactDetailsProps) {
    return (
        <div className="flex flex-col gap-4">
            {isEditing ? <ContactDetailsEdit contact={contact} cancelEdit={cancelEdit} /> : <ContactDetailsView contact={contact} />}
            {!isEditing && <Timestamps contact={contact} />}
        </div>
    );
}

function Container({ children }: React.PropsWithChildren) {
    return <div className="grid gap-4 md:grid-cols-2">{children}</div>;
}

function Section({ children, sectionName }: React.PropsWithChildren<{ sectionName: string }>) {
    return (
        <div className="rounded-lg border p-4">
            <h2 className="mb-2 font-semibold text-gray-500">{sectionName}</h2>
            <div className="space-y-2">{children}</div>
        </div>
    );
}

function Timestamps({ contact }: Pick<ContactDetailsProps, 'contact'>) {
    return (
        <div className="flex flex-col gap-2 rounded-lg border p-4">
            <small className="text-gray-500">Created on {new Date(contact.created_at).toLocaleString()}</small>
            <small className="text-gray-500">Last updated on {new Date(contact.updated_at).toLocaleString()}</small>
        </div>
    );
}

function ContactDetailsView({ contact }: Pick<ContactDetailsProps, 'contact'>) {
    return (
        <Container>
            <Section sectionName="Personal Information">
                <div>
                    <label className="text-sm font-medium text-gray-500">First Name</label>
                    <p className="text-lg">{contact.firstName}</p>
                </div>
                <div>
                    <label className="text-sm font-medium text-gray-500">Surname</label>
                    <p className="text-lg">{contact.surname}</p>
                </div>
            </Section>

            <Section sectionName="Contact Information">
                <div>
                    <label className="text-sm font-medium text-gray-500">Email</label>
                    <p className="text-lg">{contact.email}</p>
                </div>
                <div>
                    <label className="text-sm font-medium text-gray-500">Phone</label>
                    <p className="text-lg">{contact.phone}</p>
                </div>
            </Section>
        </Container>
    );
}

function ContactDetailsEdit({ contact, cancelEdit }: Pick<ContactDetailsProps, 'contact' | 'cancelEdit'>) {
    const { data, setData, put, processing, errors, reset, clearErrors, setError, isDirty } = useForm<ContactForm>(contact);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        clearErrors();

        put(route('contacts.update', { contact: contact.id }), {
            onSuccess: () => {
                onCancel();
            },
            onError: (e) => {
                if (e.errors) {
                    Object.entries(e.errors).forEach(([key, value]) => {
                        setError(key as keyof ContactForm, value);
                    });
                }
            },
        });
    };

    const onCancel = () => {
        reset();
        cancelEdit();
    };

    return (
        <form onSubmit={submit}>
            <Container>
                <Section sectionName="Personal Information">
                    <div>
                        <Label htmlFor="firstName">First Name</Label>

                        <Input
                            id="firstName"
                            type="text"
                            name="firstName"
                            value={data.firstName}
                            onChange={(e) => setData('firstName', e.target.value)}
                            required
                        />

                        <InputError message={errors.firstName} />
                    </div>
                    <div>
                        <Label htmlFor="lastName">Surname</Label>

                        <Input
                            id="surname"
                            type="text"
                            name="surname"
                            value={data.surname}
                            onChange={(e) => setData('surname', e.target.value)}
                            required
                        />

                        <InputError message={errors.surname} />
                    </div>
                </Section>

                <Section sectionName="Contact Information">
                    <div>
                        <Label htmlFor="email">Email</Label>

                        <Input id="email" type="email" name="email" value={data.email} onChange={(e) => setData('email', e.target.value)} required />

                        <InputError message={errors.email} />
                    </div>
                    <div>
                        <Label htmlFor="phone">Phone</Label>

                        <Input
                            id="phone"
                            type="tel"
                            name="phone"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            placeholder="+61412345678 or +64212345678"
                            pattern="^(\+61\d{9}|\+64\d{8,9})$"
                            title="Please enter a valid Australian (+61) or New Zealand (+64) phone number"
                            required
                        />

                        <InputError message={errors.phone} />
                    </div>
                </Section>

                <div className="mt-4 flex justify-end gap-2 md:col-span-2">
                    <Button type="button" onClick={onCancel} variant="secondary">
                        Cancel
                    </Button>
                    <Button variant="default" disabled={processing || !isDirty}>
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Edit
                    </Button>
                </div>
            </Container>
        </form>
    );
}
