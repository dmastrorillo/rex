import { useForm } from '@inertiajs/react';
import { LoaderCircle, Plus } from 'lucide-react';
import { FormEventHandler, useState } from 'react';
import InputError from '../input-error';
import { Button } from '../ui/button';
import { Dialog, DialogClose, DialogContent, DialogFooter, DialogTitle, DialogTrigger } from '../ui/dialog';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { ContactForm } from './types';

export function CreateContactModal() {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const { data, setData, post, processing, errors, reset, clearErrors, setError } = useForm<ContactForm>({
        email: '',
        firstName: '',
        surname: '',
        phone: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        const searchParams = new URLSearchParams(window.location.search);
        const currentSearch = searchParams.get('searchQuery');

        clearErrors();

        post(route('contacts.store', { _query: { searchQuery: currentSearch } }), {
            onSuccess: () => {
                closeModal();
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

    const closeModal = () => {
        setIsModalOpen(false);
        clearErrors();
        reset();
    };

    return (
        <Dialog open={isModalOpen} onOpenChange={setIsModalOpen}>
            <DialogTrigger asChild>
                <Button variant="default">
                    <Plus />
                    <span>Create Contact</span>
                </Button>
            </DialogTrigger>

            <DialogContent>
                <DialogTitle>Create Contact</DialogTitle>
                <form className="space-y-6" onSubmit={submit}>
                    <div className="grid gap-2">
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

                    <div className="grid gap-2">
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

                    <div className="grid gap-2">
                        <Label htmlFor="email">Email</Label>

                        <Input id="email" type="email" name="email" value={data.email} onChange={(e) => setData('email', e.target.value)} required />

                        <InputError message={errors.email} />
                    </div>

                    <div className="grid gap-2">
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

                    <DialogFooter className="gap-2">
                        <DialogClose asChild>
                            <Button variant="secondary">
                                Cancel
                            </Button>
                        </DialogClose>
                            <Button variant="default" disabled={processing}>
                                {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                Create contact
                            </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
