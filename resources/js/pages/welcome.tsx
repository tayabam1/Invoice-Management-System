import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import AppLogoIcon from '@/components/app-logo-icon';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Welcome">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
                <header className="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
                    <nav className="flex items-center justify-between gap-4">
                        <div className="flex items-center space-x-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-blue-600 to-purple-600 shadow-lg">
                                <AppLogoIcon className="h-6 w-6 text-white" />
                            </div>
                            <span className="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">InvoiceManager</span>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>
                <div className="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
                    <main className="flex w-full max-w-[335px] flex-col-reverse lg:max-w-4xl lg:flex-row">
                        <div className="flex-1 rounded-br-lg rounded-bl-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:rounded-tl-lg lg:rounded-br-none lg:p-20 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                            <div className="mb-6">
                                <h1 className="mb-4 text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] lg:text-4xl">
                                    Invoice Management System
                                </h1>
                                <p className="mb-6 text-lg text-[#706f6c] dark:text-[#A1A09A] lg:text-xl">
                                    Streamline your business with our powerful invoicing solution.
                                    Create, manage, and track invoices with ease.
                                </p>
                            </div>

                            <div className="mb-8 grid gap-6 lg:grid-cols-2">
                                <div className="rounded-lg border border-[#e3e3e0] bg-gradient-to-br from-blue-50 to-indigo-50 p-6 dark:border-[#3E3E3A] dark:from-blue-900/20 dark:to-indigo-900/20">
                                    <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/50">
                                        <AppLogoIcon className="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <h3 className="mb-2 text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                        Create Invoices
                                    </h3>
                                    <p className="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        Generate professional invoices quickly with customizable templates and automated calculations.
                                    </p>
                                </div>

                                <div className="rounded-lg border border-[#e3e3e0] bg-gradient-to-br from-green-50 to-emerald-50 p-6 dark:border-[#3E3E3A] dark:from-green-900/20 dark:to-emerald-900/20">
                                    <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/50">
                                        <svg className="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h3 className="mb-2 text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                        Manage Clients
                                    </h3>
                                    <p className="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        Keep track of client information, payment history, and communication in one place.
                                    </p>
                                </div>

                                <div className="rounded-lg border border-[#e3e3e0] bg-gradient-to-br from-purple-50 to-violet-50 p-6 dark:border-[#3E3E3A] dark:from-purple-900/20 dark:to-violet-900/20">
                                    <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/50">
                                        <svg className="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <h3 className="mb-2 text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                        Track Payments
                                    </h3>
                                    <p className="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        Monitor payment status, send reminders, and generate detailed financial reports.
                                    </p>
                                </div>

                                <div className="rounded-lg border border-[#e3e3e0] bg-gradient-to-br from-orange-50 to-amber-50 p-6 dark:border-[#3E3E3A] dark:from-orange-900/20 dark:to-amber-900/20">
                                    <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/50">
                                        <svg className="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 className="mb-2 text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                        Mobile Ready
                                    </h3>
                                    <p className="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                        Access your invoices anywhere with our responsive design that works on all devices.
                                    </p>
                                </div>
                            </div>

                            <div className="flex flex-col gap-3 sm:flex-row lg:justify-start">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="inline-flex items-center justify-center rounded-lg border border-transparent bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 text-base font-medium text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    >
                                        Go to Dashboard
                                        <svg className="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href={route('register')}
                                            className="inline-flex items-center justify-center rounded-lg border border-transparent bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 text-base font-medium text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        >
                                            Get Started Free
                                            <svg className="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </Link>
                                        <Link
                                            href={route('login')}
                                            className="inline-flex items-center justify-center rounded-lg border border-[#e3e3e0] bg-white px-6 py-3 text-base font-medium text-[#1b1b18] transition-all duration-200 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC] dark:hover:bg-[#1a1a19]"
                                        >
                                            Sign In
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </main>
                </div>
                <div className="hidden h-14.5 lg:block"></div>
            </div>
        </>
    );
}
