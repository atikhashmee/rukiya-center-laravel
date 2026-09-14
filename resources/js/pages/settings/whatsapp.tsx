import WhatsAppController from '@/actions/App/Http/Controllers/Settings/WhatsAppController';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { edit } from '@/routes/whatsapp';
import { type BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { Form, Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'WhatsApp settings',
        href: edit().url,
    },
];

export default function WhatsApp({
    whatsappNumber,
}: {
    whatsappNumber: string | null;
}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="WhatsApp settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="WhatsApp chat"
                        description="Number used by the floating WhatsApp button on the website. Leave empty to hide the button."
                    />

                    <Form
                        {...WhatsAppController.update.form()}
                        options={{ preserveScroll: true }}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="whatsapp_number">
                                        WhatsApp number
                                    </Label>

                                    <Input
                                        id="whatsapp_number"
                                        name="whatsapp_number"
                                        type="tel"
                                        className="mt-1 block w-full"
                                        defaultValue={whatsappNumber ?? ''}
                                        placeholder="e.g. 8801712345678 (with country code)"
                                    />

                                    <InputError
                                        message={errors.whatsapp_number}
                                    />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save</Button>

                                    <Transition
                                        show={recentlySuccessful}
                                        enter="transition ease-in-out"
                                        enterFrom="opacity-0"
                                        leave="transition ease-in-out"
                                        leaveTo="opacity-0"
                                    >
                                        <p className="text-sm text-neutral-600">
                                            Saved
                                        </p>
                                    </Transition>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
