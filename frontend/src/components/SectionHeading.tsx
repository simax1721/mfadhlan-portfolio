export function SectionHeading({
  eyebrow,
  title,
}: {
  eyebrow: string;
  title: string;
}) {
  return (
    <div className="reveal mb-12 text-center">
      <p className="font-mono text-sm uppercase tracking-widest text-accent">
        {eyebrow}
      </p>
      <h2 className="mt-2 text-3xl font-bold text-heading sm:text-4xl">
        {title}
      </h2>
      <div className="mx-auto mt-4 h-1 w-16 rounded-full bg-gradient-to-r from-accent to-accent-2" />
    </div>
  );
}
